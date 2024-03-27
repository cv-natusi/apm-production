<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Requestor;
use App\Http\Libraries\compressFile;
use App\Http\Models\Identity;
use App\Http\Models\Loket;
use App\Http\Models\Poli;
use App\Http\Models\Customer;
use App\Http\Models\rsu_customer;
use App\Http\Models\Register;
use App\Http\Models\Rawatjalan;
use App\Http\Models\Rsu_Register;
use App\Http\Models\Rsu_Rawatjalan;
use App\Http\Models\RfidApm;
use App\Http\Models\Rsu_rfidApm;
use Redirect, File, Auth;

class RFIDController extends Controller
{
    function index(){
        $this->data['classtutup'] = ' sidebar-collapse';
        return view('Admin.rfid.main')->with('data', $this->data);
    }

    function cek(Request $request){
        // $cekRfidByRfid = RfidApm::where('noRfid',$request->noRM)->first(); // db lokal
        $cekRfidByRfid = Rsu_rfidApm::where('noRfid',$request->noRM)->first(); // db rsu
        if(count($cekRfidByRfid)==1){
            $rm = $cekRfidByRfid->KodeCust;
        }else{
            $rm = $request->noRM;
        }
        // $cekCustomer = Customer::where('KodeCust',$rm)->first(); // db lokal
        $cekCustomer = rsu_customer::where('KodeCust',$rm)->first(); // db rsu
        if(count($cekCustomer)!=0){
            // $cekRfid = RfidApm::where('KodeCust',$cekCustomer->KodeCust)->first(); // db lokal
            $cekRfid = Rsu_rfidApm::where('KodeCust',$cekCustomer->KodeCust)->first(); // db rsu
            if (count($cekRfid) != 0) {
                $dataRfid = $cekRfid;
                $statusRfid = 'Exist';
            }else{
                $dataRfid = '';
                $statusRfid = 'Nothing';
            }
            $status='success';
            $message = 'Data Exist';
            $data = [
                'data'=>$cekCustomer,
                'statusRfid'=>$statusRfid,
                'dataRfid'=>$dataRfid,
            ];
            $content = view('Admin.rfid.detailCustomer',$data)->render();
        }else{
            $status='error';
            $message = 'Data Not Exist';
            $data = '';
            $dataRfid = '';
            $statusRfid = 'Nothing';
            $content = '';
        }
        // return ['status'=>$status,'message'=>$message,'data'=>$data,'statusRfid'=>$statusRfid,'dataRfid'=>$dataRfid];
        return ['status'=>$status,'message'=>$message,'data'=>'','content'=>$content];
    }

    public function formAddRfid(Request $request)
    {
        // $data['customer'] = Customer::where('KodeCust',$request->id)->first(); // db lokal
        $data['customer'] = rsu_customer::where('KodeCust',$request->id)->first(); // db rsu
        $content = view('Admin.rfid.formAddRfid', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function addRfid(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        // $existRFIDByRFID = RfidApm::where('noRfid',$request->noRfid)->first(); // db lokal
        $existRFIDByRFID = Rsu_rfidApm::where('noRfid',$request->noRfid)->first(); // db rsu
        if(count($existRFIDByRFID)==0){
            // $existRFID = RfidApm::where('KodeCust',$request->KodeCust)->first(); // db lokal
            $existRFID = Rsu_rfidApm::where('KodeCust',$request->KodeCust)->first(); // db rsu
            if(count($existRFID)==0){
                // $rfid = new RfidApm; // db lokal
                $rfid = new Rsu_rfidApm; // db rsu
                $judul = 'Tambah';
            }else{
                // $rfid = RfidApm::where('KodeCust',$request->KodeCust)->first(); // db lokal
                $rfid = Rsu_rfidApm::where('KodeCust',$request->KodeCust)->first(); // db rsu
                $judul = 'Ubah';
            }
            $rfid->KodeCust = $request->KodeCust;
            $rfid->noRfid = $request->noRfid;
            $rfid->created = date('Y-m-d H:i:s');
            $rfid->save();

            if ($rfid) {
                $rm = $request->KodeCust;
                // $cekCustomer = Customer::where('KodeCust',$rm)->first(); // db lokal
                $cekCustomer = rsu_customer::where('KodeCust',$rm)->first(); // db rsu
                if(count($cekCustomer)!=0){
                    // $cekRfid = RfidApm::where('KodeCust',$cekCustomer->KodeCust)->first(); // bd lokal
                    $cekRfid = Rsu_rfidApm::where('KodeCust',$cekCustomer->KodeCust)->first(); // bd rsu
                    if (count($cekRfid) != 0) {
                        $dataRfid = $cekRfid;
                        $statusRfid = 'Exist';
                    }else{
                        $dataRfid = '';
                        $statusRfid = 'Nothing';
                    }
                    $status='success';
                    $message = 'Data Exist';
                    $data = [
                        'data'=>$cekCustomer,
                        'statusRfid'=>$statusRfid,
                        'dataRfid'=>$dataRfid,
                    ];
                    $content = view('Admin.rfid.detailCustomer',$data)->render();
                }else{
                    $status='error';
                    $message = 'Data Not Exist';
                    $data = '';
                    $dataRfid = '';
                    $statusRfid = 'Nothing';
                    $content = '';
                }
                return ['status'=>'success','message'=>$judul.' RFID Berhasil Dilakukan !!','content'=>$content];
            }else{
                return ['status'=>'error','message'=>$judul.' RFID Gagal Dilakukan !!'];
            }
        }else{
            return ['status'=>'error','message'=>'RFID sudah digunakan'];
        }
    }

    function cetakRfid(Request $request){
        // $data['customer'] = Customer::where('KodeCust',$request->id)->first(); // db lokal
        $data['customer'] = rsu_customer::where('KodeCust',$request->id)->first(); // db rsu
        $content = url('/').'/admin/rfid/cetak/'.$request->id;
        return ['status' => 'success', 'url' => $content];
    }

    function cetak(Request $request){
        // $data['customer'] = Customer::where('KodeCust',$request->id)->first(); // db lokal
        $data['customer'] = rsu_customer::where('KodeCust',$request->id)->first(); // db rsu
        $content = url('/').'admin/rfid/cetak/'.$request->id;
        return view('Admin.rfid.cetak',$data);
    }
}
