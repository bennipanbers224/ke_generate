<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\data_file;
use Smalot\PdfParser\Parser;
use setasign\Fpdi\Tcpdf\Fpdi;
use PDF;
use Illuminate\Support\Facades\Hash;
use phpseclib\Crypt\RSA;
use Illuminate\Support\Facades\Http;

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
              
        $data = data_file::create([
            'user_id'=>auth()->user()->id,
            'file_name'=>$fileName,
            'status'=>"Requested",
        ]);
        return back()->with('success', 'success for generate signature')->with('file',$fileName);
    }

    public function detail($id){
        $data = data_file::select('data_files.id', 'data_files.file_name', 'data_files.status as status_file', 'users.name', 'users.status')
                ->join("users", "data_files.user_id", "=", "users.id")->where("data_files.id", "=", $id)->first();

        return view('signature.detail')->with(compact('data'));
    }

    public function getVerificationResult(Request $request){

        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $fileName = $request->file->getClientOriginalName();

        $request->file->move(public_path('verify_file'), $fileName);

        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile(public_path('verify_file/'.$fileName));

        $content = $pdf->getText();

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'http://localhost:80/api_master_key_generate/verify.php', [
            'form_params' => [
                'data' => $content,
            ]
        ]);

        $bodyresponcs = $response->getBody();
        $result = json_decode($bodyresponcs);
        if($result->message == "File is fully original"){
            return back()->with('success', $result->message)->with('file',$fileName);
        }
        else{
            return back()->with('error', $result->message)->with('file',$fileName);
        }

        // echo $content; 
        // echo $result->status;
        // echo "<br>".$result->message_digest;

    }

    public function signing(Request $request){

        $fileName = $request->file_name;

        $this->fillPDFFile(public_path('upload/'.$fileName), public_path('upload/'.$fileName), $fileName);

        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile(public_path('upload/'.$fileName));

        $content = $pdf->getText();

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'http://localhost:80/api_master_key_generate/generate.php', [
            'form_params' => [
                'data' => $content,
                'file_id' => $request->file_id,
            ]
        ]);

        $bodyresponcs = $response->getBody();
        $result = json_decode($bodyresponcs);
        if($result->status == 200){
            $data_file = data_file::find($request->file_id);
            $data_file->status = "Done";
            $data_file->save();
            return back()->with('success',"Success for generate signature of this file");
        }
        else{
            return back()->with('error',"Fail for generate signature of this file");
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

}
