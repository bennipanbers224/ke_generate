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

        $privateKey = "-----BEGIN RSA PRIVATE KEY----- MIIJKAIBAAKCAgEApL79WlkvHCJvTp/BEV0t8v8AGNnyHScqVJyrdq+t6x/BNcGr RKJwah2W4V/uX40HMBLWQUyKju1X65xZv+gu17WfXrjg7omTczozeoo+fi3ZGqfO AGKwMdQLJ4NqT4eAjxTE7JS2afXsL0YvSF899W8GAGPQAy5En4GkvdEzOkxj8De7 oG5x4maexJIChicE0+aAjlnVn9Eb3Gf8hvsy9xjae+nobN5xKAPsI/knvd02UwEn If6xn4uRkpqeTG7LA7hoG1yNAakTsqXEY4O6S8fEW53eMqfj3+wD16n8LT1j0wX4 UkCx0jb7QXm3nFtr09KEUHBduoX676KkDbXfswkq47U2eJB1q/bwyrM0TKhV4Nlp H1UB3H3H9OjVdVsrg45GEF6+Fe78Uj0qcMTOqpeXahTMGue9aLt2ENkt2Cf+hXCT oBd9RKlTuL3p53LxwvLLyzPfBVWlXgggQLEkTKJxK1IULtMmUIun5sb7aJ+PzcyG yqCXzhLCqIB+p5lhKKbwcBdrvehDt9zLkL2BTdIvzuWeUH7V6wiFIJjUace5ut3t 8lJV+qdqNyHWw2AZGj6F1n3oJhuuNsNkSzTRkmQbfife3eE9/IqzML/zaABjWUOy Y0zEr2p7WiGU6GkH57oeeSgWh3uKND5nK5Vth9cFUPTKZkiH4K0QxJ6ScNcCAwEA AQKCAgA5HutzHwcBh0L2jNeBMMCUxyt+clrLFuBLdALTZPg1VM5F2D+MzZGSiW3d +MgKozhBx9Y3z9Ue11YroAtSSausKRH34rxCmLriMeTVcQdX8oebAWGgqpcSv8Wa TqtWpdHCSl449RsGRDk+7uPIARXXekYnbnQaFjm+4FGBav6LER95k6lfZ8vextv3 pfRwFSlMW1hpguZZwkQZuIhLON+j4x8l5aUr0jiDulBu7J2F7CRAxV6PH+LwQZEY trl6INQNFywpiH5ve/MTdvxP0MWQLtR7O5JmF+YfppJyfUKtYOKysRg3VK0TCPM2 8X/jG6OYTx6HUV5+JKQr4EVnZ48G0xa09SiIMXGEJ5Y77E8NS9+o0M14NTOqVV+Y 6JFuK2/7vYIq3bs2hTnShZSeyxb8Ugl8wo7i/Vg0ateymmUhzcIa9KVZJvEyasqI axRewEUgT44kN2mn42QTTZruoCbJZJz9VV7cYJSO4fsJsHs+QmQRSwSzw5RBE0Hi F87qHIq00E8tgJFduUXWwYVlObcTV8+TvQpfhA48rVGkHJvcSl8BqrGT33EDCN8V HeVlJww56cCY6TKF0K7rJ9U4f0JrKYJTZIawMomfastSHZ8IWDooWoYpn0Gq08A2 FDXmDHYgjog5cZkmAN79dyIfAEkCQQ2Cx2FgaS9HuQZEiKGuWQKCAQEAzXj3PHoO 0K/R77ugvsHR3pbaAIA8ioRjTorKekPNIQXxeHZw2uSYAv5yhl5HN3yEfRei2Fs4 TzKXlucSpnnkSZHwb4kmW3yg6N7tQv5uy2w+4jCR26+qs/mzFEH/E6sYkwF/QhD6 J2fanlf+HT8NOp7qM18WG9oG11qeAFKPJhJc0zIR5JuHZG2pE6hi14Iy7oZjzci6 cYcqJnX9Oc6CG54bfsyQPd3YRROlMkiPLFNHq3nJl+Vz1jo7yPOHVRlg5O810ipC 5llCThY6cCtgusGtpA7KtLjNPyRuyxZmLc6+xWeXBXK2gtbMOVo93SxmrXOu8dfc m+mZDK3PwAsZ9QKCAQEAzUIvkvsD0ydjEWvx/W0zRYcMhS08wS+NBhLEpuVPnuAa DOlt98Ene7Wu9gV4BhiekmU0Ku0Wge3QpYVBBxJ+4flYGiwVqlsXXWTRL2fIl29W be4Fck0gglQiLJzr5szKtNGKnuMiSnY3kwD7bTFB1en2xbCrd/oCxIm+y7vojZI4 TBDcUHS6kbiQD3KTESGOYSrQlSVme0pObJ4WzoBp83m0mbBdxq2TTkWaecj0x7+6 YAF8jy18JYRAHwo0uKCEu05XVOeZVSjdkAfJ2y/HSe31QFJmRlR7PMGq7Lplf6m4 NVyheERaCOyRGxpbNefCt8LeIPVm2CpRlRNSQVdkGwKCAQAnBiV2v/vWWL43mNwT vr38V4w58iENcc1mmmeQRxZLglR1O5F9kVLppqfbq0Y9XJHRLE8KZnDQYdx2lZZu NdJMwsH2fQbi802gTM6lHauvSPS8gtsA7WEvY2pGm641bxZwvSUNSCoql8fmsPGb XmFF0+7JKPMLASKxfg1qJEP5yB0HI4hQcdWKFmW5TQ7QvXKs6KtF5yy56SDxQxGA DB+b3TB9qZ5vkZXEsNF8Nlmy4nJHOA84oSSZ+F9ocBAw8mv7rw76xnoEQ8mbCQ+6 RBLQBX7UHNcU8dBRgLrmKW/onU6QMXd2Wm4ddWw+LEx5mDg8Ey+T0RqeJgSiatR9 Nd6NAoIBAQCG79fuvG5LRj1umpQ/kqZ+MsYcVxz1s5j2QShev6mUPKdjjaHpmmWm gk4/FmFNQKoee8HL5olBaWIdLnkNeS9Ix28P5aNolvHJSBntHEFWrK8ZHb0e421X wI+m3JKI7GDmdkq3IYLNiOIIFkE0C4nIY0MDu50+BnIDWrq/nQzI5TN1bjAoBFUS r06lmSDtxi2f21G8exXgP31HErbTksAUfBIqZzN/BWUts7xWC7JADt8rdk64oaJX 15V/0mnOZlhb+qiW/JpkzbS6lyQwoDxXccQlQQZTff52dqszbWaEgsLC8d6E/gpi i4LpWm5UBcSQod79s9akjU2Mv2L36ce5AoIBAEPF1PlSdp1882GZX6HWFEH4I/AA 8fktfVYCmZOa6v9V1klyLn658fO4htCjqIjaPVWJlgnrt6F5C1/FeKEyIKvF5wLL 5aPRzh/MO4UaIYBNUiPDZhkZgNVn94AqDSExxh4dwIwhfvkzu008CTvR4OaW4CbY 7A2gm0VG5i6yuf8bXDvmZ89OSJDm24i7J7Xly++b4bS3A/hMCV9h4ncD3w751EF9 IRJ8YHX87EwkTBX3QebSE5f6AZmjhmEI/9SJ2ybCU91Kg53jUjtKAqQBaPllTy2e sY+zRabP1APAR5z8gOjPzo3LqWy1wWC3XxeGcSvU+cn8LbQekpNOTZtTtpA= -----END RSA PRIVATE KEY-----";


        $signature = $this->encryptData($message, $privateKey);

       
              
        $data = data_file::create([
            'name'=>$nama[0],
            'message_digest'=>$message,
            'signature'=>$signature,
        ]);
        return back()->with('success', 'success for generate signature')->with('file',$fileName);
    }

    public function getVerificationResult(Request $request){

        $publicKey = "-----BEGIN PUBLIC KEY----- MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEApL79WlkvHCJvTp/BEV0t 8v8AGNnyHScqVJyrdq+t6x/BNcGrRKJwah2W4V/uX40HMBLWQUyKju1X65xZv+gu 17WfXrjg7omTczozeoo+fi3ZGqfOAGKwMdQLJ4NqT4eAjxTE7JS2afXsL0YvSF89 9W8GAGPQAy5En4GkvdEzOkxj8De7oG5x4maexJIChicE0+aAjlnVn9Eb3Gf8hvsy 9xjae+nobN5xKAPsI/knvd02UwEnIf6xn4uRkpqeTG7LA7hoG1yNAakTsqXEY4O6 S8fEW53eMqfj3+wD16n8LT1j0wX4UkCx0jb7QXm3nFtr09KEUHBduoX676KkDbXf swkq47U2eJB1q/bwyrM0TKhV4NlpH1UB3H3H9OjVdVsrg45GEF6+Fe78Uj0qcMTO qpeXahTMGue9aLt2ENkt2Cf+hXCToBd9RKlTuL3p53LxwvLLyzPfBVWlXgggQLEk TKJxK1IULtMmUIun5sb7aJ+PzcyGyqCXzhLCqIB+p5lhKKbwcBdrvehDt9zLkL2B TdIvzuWeUH7V6wiFIJjUace5ut3t8lJV+qdqNyHWw2AZGj6F1n3oJhuuNsNkSzTR kmQbfife3eE9/IqzML/zaABjWUOyY0zEr2p7WiGU6GkH57oeeSgWh3uKND5nK5Vt h9cFUPTKZkiH4K0QxJ6ScNcCAwEAAQ== -----END PUBLIC KEY-----";



        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $fileName = $request->file->getClientOriginalName();

        $request->file->move(public_path('verify_file'), $fileName);

        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile(public_path('verify_file/'.$fileName));

        $content = $pdf->getText();

        $file_messageDigest = hash('sha256', $content);

        $result = data_file::where('message_digest', '=', $message)->first();

        if($result!=NULL){

            $database_messageDigest = $this->decryptData($result['signature'], $publicKey);

            if($file_messageDigest == $database_messageDigest){
                return back()->with('success', 'Your certificate is fully original')->with('file',$fileName)->with(compact('data'));
            }
            else{
                return back()->with('error', 'Your certificate is not original')->with('file',$fileName);
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
        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        $ciphertext = base64_decode($data);
        $rsa->loadKey($publicKey);
        $output = $rsa->decrypt($ciphertext);
        return $output;
    }

}
