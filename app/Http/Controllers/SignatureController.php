<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\data;
use Smalot\PdfParser\Parser;
use setasign\Fpdi\Tcpdf\Fpdi;
use PDF;
use Illuminate\Support\Facades\Hash;
use phpseclib\Crypt\RSA;

class SignatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    public function generateKey(){
        return view("signature.signature");
    }

    public function verify(){
        return view("signature.verify");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);
  
        $fileName = time().'.'.$request->file->extension();  
   
        $request->file->move(public_path('upload'), $fileName);

        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile(public_path('upload/'.$fileName));

        $content = $pdf->getText();


        //get nama dari file pdf
        $nama = explode("Lahir di", explode("menyatakan bahwa", $content)[1]);


        //message digest encryption
        $message = hash('md5', $request->file);

        $dataKey = explode(":",env("APP_KEY"));
        $key = $dataKey[1];

        $data = $this->encryptthis($message, $key);


        //line for sign file
        $this->fillPDFFile(public_path('upload/'.$fileName), public_path('upload/'.$fileName), $fileName, $data);
              
        $data = data::create([
            'name'=>$nama[0],
            'public_key'=>$message,
        ]);
        return back()->with('success', 'success for generate signature')->with('file',$fileName);
    }

    //encrypt message digest
    public function encryptthis($data, $key) {
        $encryption_key = base64_decode($key);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    //decrypt message digest
    function decryptthis($data, $key) {
        $encryption_key = base64_decode($key);
        list($encrypted_data, $iv) = array_pad(explode('::', base64_decode($data), 2),2,null);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
    }

    //fungsi untuk verifikasi
    public function getVerificationResult(Request $request){
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $fileName = $request->file->getClientOriginalName();

        $request->file->move(public_path('verify_file'), $fileName);

        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile(public_path('verify_file/'.$fileName));
        $content = $pdf->getText();

        //checking if the file has key inside
        if(str_contains($content, "====")){
            $keyEncrypted = explode("====", $content);

            $dataKey = explode(":",env("APP_KEY"));
            $key = $dataKey[1];       

            $encrypted = $keyEncrypted[0];
            $decrypted = $this->decryptthis($encrypted, $key);

            if(!empty($decrypted)){
                $data = data::where('public_key', 'like', "%{$decrypted}%")->get();

                if(count($data)>0){
                    return back()->with('success', 'Your certificate is fully original')->with('file',$fileName)->with(compact('data'));
                }
                else{
                    return back()->with('error', 'Your certificate not fount for verification')->with('file',$fileName);
                }
            }
            else{
                return back()->with('error', 'Your certificate not fount for verification')->with('file',$fileName);
            }
        }
        else{
            return back()->with('error', 'Your certificate not fount for verification')->with('file',$fileName);
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    //function for generate pdf using certificate
    public function fillPDFFile($file, $outputFilePath, $fileName, $privateKey)
    {
        $fpdi = new FPDI('P', 'mm', 'A4');
          
        $count = $fpdi->setSourceFile($file);

        $certificate = 'file://'.base_path().'/public/certificate/tcpdf.crt';

        $info = array(
            'Name' => 'TCPDF',
            'Location' => 'Office',
            'Reason' => 'Testing TCPDF',
            'ContactInfo' => 'http://www.tcpdf.org',
        );
  
        for ($i=1; $i<=$count; $i++) {
  
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            $fpdi->SetTextColor(0,0,0);
            
            $left = 10;
            $top = 10;
            $text = $privateKey."====";
            $fpdi->Text($left,$top,$text);

            $fpdi->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);
        }
  
        return $fpdi->Output($outputFilePath, 'F');
    }

}
