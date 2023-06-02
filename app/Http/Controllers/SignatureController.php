<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\data_file;
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
        
        $this->fillPDFFile(public_path('upload/'.$fileName), public_path('upload/'.$fileName), $fileName);

        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile(public_path('upload/'.$fileName));

        $content = $pdf->getText();

        $nama = explode("Lahir di", explode("menyatakan bahwa", $content)[1]);

        $message = hash('sha256', $content);

        $rsa = new \Crypt_RSA();
        $keys = $rsa->createKey();
        // $publickey = $keys['publickey'];
        $privateKey = $keys['privatekey'];

        $signature = $this->encryptData($message, $privateKey);

       
              
        $data = data_file::create([
            'name'=>$nama[0],
            'message_digest'=>$message,
            'signature'=>$signature,
        ]);
        return back()->with('success', 'success for generate signature')->with('file',$fileName);
    }

    public function getVerificationResult(Request $request){


        $rsa = new \Crypt_RSA();
        $keys = $rsa->createKey();
        $publicKey = $keys['publickey'];


        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $fileName = $request->file->getClientOriginalName();

        $request->file->move(public_path('verify_file'), $fileName);

        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile(public_path('verify_file/'.$fileName));

        $content = $pdf->getText();

        $file_messageDigest = hash('sha256', $content);

        $result = data_file::where('message_digest', '=', $file_messageDigest)->first();

        if($result!=NULL){

            $database_messageDigest = $this->decryptData($result['signature'], $publicKey);

            if($file_messageDigest == $database_messageDigest){
                // return back()->with('success', 'Your certificate is fully original')->with('file',$fileName)->with(compact('data'));

                echo "true";
            }
            else{
                // return back()->with('error', 'Your certificate is not original')->with('file',$fileName);
                echo "false";
            }
        }
        else{
            return back()->with('error', 'Your certificate is not original')->with('file',$fileName);
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
    public function fillPDFFile($file, $outputFilePath, $fileName)
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

            $fpdi->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);
        }
  
        return $fpdi->Output($outputFilePath, 'F');
    }

    function encryptData($data, $publicKey) {
        $rsa = new \Crypt_RSA();
        $rsa->loadKey($publicKey);
        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        $output = $rsa->encrypt($data);
        return base64_encode($output);
    }

    function decryptData($data, $publicKey) {
        $rsa = new \Crypt_RSA();
        $rsa->setDecryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        $ciphertext = base64_decode($data);
        $rsa->loadKey($publicKey);
        $output = $rsa->decrypt($ciphertext);
        return $output;
    }

}
