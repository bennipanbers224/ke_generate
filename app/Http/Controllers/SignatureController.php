<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\data;
use Smalot\PdfParser\Parser;
use setasign\Fpdi\Fpdi;
use PDF;

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
            'file' => 'required|mimes:pdf,xlx,csv|max:2048',
        ]);
  
        $fileName = time().'.'.$request->file->extension();  
   
        $request->file->move(public_path('upload'), $fileName);

        $this->fillPDFFile(public_path('upload/'.$fileName), public_path('upload/'.$fileName), $fileName);
              
        $data = data::create([
            'nama'=>$fileName,
            'private_key'=>"iVBORw0KGgoAAAANSUhE"
        ]);
        return back()->with('success', 'success Full upload signature')->with('file',$fileName);
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
              
            $fpdi->SetFont("helvetica", "", 15);
            $fpdi->SetTextColor(153,0,153);

            $left = 10;
            $top = 10;
            $text = "====Key Generate : ".md5($fileName)."====";
            $fpdi->Text($left,$top,$text);
        }
  
        return $fpdi->Output($outputFilePath, 'F');
    }
}
