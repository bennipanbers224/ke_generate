<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\data_file;
use App\Models\manual_files;
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

            $result = data_file::where('nim', '=', $result->nim)->first();

            return back()->with('success', "File is fully original")->with('file',$fileName)->with(compact('result'));
        }
        else{
            return back()->with('error', "File not original")->with('file',$fileName);
        }

    }

    public function manualSigning(Request $request){
        $fileName = $request->nim.'.'.$request->file->extension();

        $result = data_file::where('nim', '=', $request->nim)->get();

        if(count($result)>0){
            $data = data_file::create([
                'name'=>$request->name,
                'nim'=>$request->nim,
                'major'=>$request->major,
                'file'=>$fileName,
            ]);
    
            $data['name'] = $request->name;
            $data['nim'] = $request->nim;
            $data['major'] = $request->major;
    
            return back()->with('error',"This Data has been exist")->with('file',$fileName)->with(compact('data'));
        }
        else{

            $request->file->move(public_path('upload'), $fileName);

            $this->fillPDFFile(public_path('upload/'.$fileName), public_path('upload/'.$fileName), $fileName);

            $pdfParser = new Parser();
            $pdf = $pdfParser->parseFile(public_path('upload/'.$fileName));

            $content = $pdf->getText();

            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', 'http://localhost:80/api_master_key_generate/generate.php', [
                'form_params' => [
                    'data' => $content,
                    'nim' => $request->nim,
                ]
            ]);

            $bodyresponcs = $response->getBody();
            $result = json_decode($bodyresponcs);
            if($result->status == 200){
                $data = data_file::create([
                    'name'=>$request->name,
                    'nim'=>$request->nim,
                    'major'=>$request->major,
                    'file'=>$fileName,
                ]);
        
                $data['name'] = $request->name;
                $data['nim'] = $request->nim;
                $data['major'] = $request->major;
        
                return back()->with('success',"Success for generate signature of this file")->with('file',$fileName)->with(compact('data'));
            }
            else{

                $data['name'] = $request->name;
                $data['nim'] = $request->nim;
                $data['major'] = $request->major;

                return back()->with('error',"Fail for generate signature of this file")->with('file',$fileName)->with(compact('data'));
            }
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
