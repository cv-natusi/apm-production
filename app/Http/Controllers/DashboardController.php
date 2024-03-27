<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Loket;
use App\Http\Models\Users;
use App\Http\Models\rsu_customer;
use App\Http\Models\Customer;
use App\Http\Models\Poli;
use App\Http\Models\Tracer;
use App\Http\Models\Rawatjalan;
use App\Http\Models\Rsu_Tracer;
use App\Http\Models\Rsu_Rawatjalan;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Http\Libraries\Requestor;
use App\Http\Libraries\Formatters;

use Redirect;

class DashboardController extends Controller
{
    public function index(Request $request){
        $this->data['sumnoantrian']= Loket::where('tgl_antrian',date('Y-m-d'))->sum('id');
        return view('loket')->with('data',$this->data);
    }

    public function indexAdmin()
    {
        $this->data['classtutup'] = 'sidebar-collapse';
        $this->data['mn_active'] = 'dashboard';
        
        $this->data['poli'] = Poli::distinct('NamaPoli')->select('NamaPoli')->get();
        return view('Admin.dashboard.main')->with('data',$this->data);
    }

    public function nomorantrian(Request $request){
        $nomorantrianakhir = Loket::orderby('id','desc')->first();
        $cekrm = Customer::where('KodeCust',$request->rm)->first();
        if($cekrm){
            $nomor = New Loket;
            $nomor->no_antrian = $nomorantrianakhir->no_antrian+1;
            $nomor->status = 0;
            $nomor->no_customer = $request->rm;
            $nomor->kode_poli = ($request->poli) ? $request->poli : '';
            $nomor->tgl_antrian = date('Y-m-d');
            $nomor->save();

            if($nomor){
                return Redirect::route('quest')->with('title','Terima Kasih')->with('message','Silahkan Ambil nomor Antrian ('.$nomor->no_antrian.')dan Silahkan duduk')->with('type','success');
            }else{
                return Redirect::route('quest')->with('title','Maaf !!!')->with('message','Sistem Sedang Dalam Perbaikan, Silahkan hubungi bagian administrasi')->with('type','error');
            }
        }else{
            return Redirect::route('quest')->with('title','Maaf !!!')->with('message','Anda Tidak terdaftar dalam sistem, Silahkan hubungi bagian administrasi')->with('type','error');
        }
    }

    public function sendSms()
    {
        // Script Kirim SMS Api Zenziva
        $nama = "Zein Saedi";
        $userkey="mv4yhj"; // userkey lihat di zenziva
        $passkey="nikyuzi"; // set passkey di zenziva
        $telepon = '085731712672';
        $message="Terima Kasih, pendaftaran telah berhasil di zeinganteng.com.  Harap Maklum";

        $url = "http://reguler.zenziva.net/apps/smsapi.php";
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey='.$userkey.'&passkey='.$passkey.'&nohp='.$telepon.'&pesan='.urlencode($message));
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        $results = curl_exec($curlHandle);
        curl_close($curlHandle);

        return $results;
    }

    public function mail(Request $request){
       //PHPMailer Object
        $mail = new PHPMailer;
        $mail->SMTPDebug = 3;                           
 
        //Set PHPMailer to use SMTP.
         
        $mail->isSMTP();        
        $mail->SMTPAuth = true; 
        //Set SMTP host name                      
         
        $mail->Host = "smtp.gmail.com";
        $mail->Username = "win.iskand69@gmail.com";             
        $mail->Password = "win.iskand69";                       
        //If SMTP requires TLS encryption then set it
        $mail->SMTPSecure = "tls";    
        //From email address and name
        $mail->From = "win.iskand69@gmail.com";
        $mail->FromName = "Bang Iskand";

        //To address and name
        $mail->addAddress("iskand69@gmail.com", "iskandar");
        // $mail->addAddress("iskand69@gmail.com"); //Recipient name is optional

        //Address to which recipient will reply
        $mail->addReplyTo("win.iskand69@gmail.com", "Reply");

        //CC and BCC
        // $mail->addCC("cc@example.com");
        // $mail->addBCC("bcc@example.com");

        //Send HTML or Plain Text email
        $mail->isHTML(true);

        $mail->Subject = "Subject Text";
        $mail->Body = "<i>Mail body in HTML</i>";
        $mail->AltBody = "This is the plain text version of the email content";

        if(!$mail->send()) 
        {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } 
        else 
        {
            echo "Message has been sent successfully";
        }
    }


    public function getkunjungan(Request $request){
        // return $request->all();
        if($request->kat == 'rj'){
            // $polis = Rawatjalan::whereBetween('Tanggal',[$request->start.' 00:00:00',$request->end.' 23:59:59'])->select('No_Register')->get(); // db lokal 
            $polis = Rsu_Rawatjalan::whereBetween('Tanggal',[$request->start.' 00:00:00',$request->end.' 23:59:59'])->select('No_Register')->get(); // db rsu 
            $title = "Data Kunjungan Pasien Rawat Jalan";
            $subtitle = Formatters::tgl_indo($request->start).' - '.Formatters::tgl_indo($request->end);
        }else{
            $title = "Data Kunjungan Pasien Rawat Inap";
        }

        // berdasarkan poli
        if($request->jenis == 'all'){
            // $poli = Tracer::distinct('NamaPoli') // db lokal
            $poli = Rsu_Tracer::distinct('NamaPoli') // db rsu
                        ->select('NamaPoli')
                        ->whereBetween('Tgl_Register',[$request->start.' 00:00:00',$request->end.' 23:59:59'])
                        ->whereIn('No_Register',$polis)
                        ->get();
            // $poli = Rsu_Tracer::distinct('NamaPoli')->select('NamaPoli')->whereIn('No_Register',$poli)->get(); // db rsu
        }else{
            $title .= ' '.$request->jenis;
            /*$poli = Tracer::distinct('NamaPoli')->select('NamaPoli')
                        ->whereIn('No_Register',$polis)
                        ->where('NamaPoli',$request->jenis)->get(); // db lokal*/
            // $poli = Tracer::distinct('NamaPoli') // db lokal
            $poli = Rsu_Tracer::distinct('NamaPoli') // db rsu
                        ->select('NamaPoli')
                        ->whereBetween('Tgl_Register',[$request->start.' 00:00:00',$request->end.' 23:59:59'])
                        ->whereIn('No_Register',$polis)
                        ->where('NamaPoli',$request->jenis)
                        ->get(); // db lokal
        }

        $a = 0;
        if($poli->count() > 0){
            if($request->jenis == 'all'){
                foreach ($poli as $pol) {
                   /* $total = Tracer::where('NamaPoli',$pol->NamaPoli)
                                ->whereBetween('Tgl_Register',[$request->start.' 00:00:00',$request->end.' 00:00:00'])
                                ->count('NamaPoli'); // db lokal*/
                    // $total = Tracer::where('NamaPoli',$pol->NamaPoli) // db lokal
                    $total = Rsu_Tracer::where('NamaPoli',$pol->NamaPoli) // db rsu
                                ->whereBetween('Tgl_Register',[$request->start.' 00:00:00',$request->end.' 23:59:59'])
                                ->count('NamaPoli'); // db rsu
                    $totkun[$a] = [
                        'poli' => $pol->NamaPoli,
                        'sum' => $total,
                    ]; 
                    $a++;
                }
            }else{
                $start = $request->start;
                $end = $request->end;
                $selisih = ((abs(strtotime ($start) - strtotime ($end)))/(60*60*24));

                for ($i=0; $i <= $selisih; $i++) { 
                    $tgl = date('Y-m-d', strtotime('+'.$i.' days', strtotime($start)));

                  /*  $total = Tracer::where('NamaPoli',$request->jenis)
                                ->where('Tgl_Register','like',$tgl.'%')
                                ->count('NamaPoli'); // db lokal*/
                    // $total = Tracer::where('NamaPoli',$request->jenis) // db lokal
                    $total = Rsu_Tracer::where('NamaPoli',$request->jenis) // db rsu
                                        ->where('Tgl_Register','like',$tgl.'%')
                                        ->count('NamaPoli');
                    $totkun[$i] = [
                        'poli' => $tgl,
                        'sum' => $total,
                    ]; 
                }
            }
        }else{
            $totkun = '';
        }

        return ['totkun'=>$totkun, 'title'=>$title, 'subtitle'=>$subtitle,'jenis'=>$request->jenis];
    }
}
