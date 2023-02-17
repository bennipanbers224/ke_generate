<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\data;
use Smalot\PdfParser\Parser;
use setasign\Fpdi\Fpdi;
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
              
        $data = data::create([
            'name'=>"Glesia Putra Silalahi",
            'major'=>"Sistem Informasi",
            'title'=>"Sarjana Komputer (S.Kom.)",
            'predicate'=>"Sangat Memuaskan",
            'graduation_date'=>"15 Agustus 2022",
            'start_study'=>"2018/2019",
            'nim'=>"14S18004",
            'certificate_number'=>"202012022000360",
            'image'=>$fileName,
            'private_key'=>md5($fileName)
        ]);
        return back()->with('success', 'success for generate signature')->with('file',$fileName);
    }

    public function getVerificationResult(Request $request){
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $fileName = $request->file->getClientOriginalName().'.'.$request->file->extension();

        $request->file->move(public_path('verify_file'), $fileName);

        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile(public_path('verify_file/'.$fileName));
        $content = $pdf->getText();

        $key = explode("====", $content);

        // echo $key[0];

        $data = data::where('private_key', '=', $key[0])->get();

        // var_dump($data);

        if(count($data)>0){
            return back()->with('success', 'Your certificate is fully original')->with('file',$fileName)->with(compact('data'));
        }
        else{
            return back()->with('error', 'Your certificate not fount for verification')->with('file',$fileName)->with(compact('data'));
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

    public function fillPDFFile($file, $outputFilePath, $fileName)
    {
        $fpdi = new FPDI;
          
        $count = $fpdi->setSourceFile($file);
  
        for ($i=1; $i<=$count; $i++) {
  
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);
            
            $fpdi->SetFont("arial", "", 15);
            $fpdi->SetTextColor(0,0,0);

            $left = 10;
            $top = 10;
            $text = Hash::make($fileName)."====";
            $rsa = new RSA();
            $keys = $rsa->createKey(4096);
            $publicKey = $keys['publickey'];
            $privateKey = $keys['privatekey'];
            $fpdi->Text($left,$top,$publicKey);
        }
  
        return $fpdi->Output($outputFilePath, 'F');
    }
}
