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
use App\Http\Models\Register;
use App\Http\Models\Rawatjalan;
use App\Http\Models\CC;
use App\Http\Models\Rsu_Register;
use App\Http\Models\Rsu_Rawatjalan;
use App\Http\Models\Rsu_RawatJalanObat;
use App\Http\Models\Rsu_RawatJalanTindakan;
use App\Http\Models\Rsu_cc;
use Redirect, File, Auth, DB;
class AdminController extends Controller
{
    public function antrianmain(Request $require){
        $this->data['classtutup'] = ' sidebar-collapse';
        $this->data['poli'] = Poli::all();
        $nosisaantrian = Loket::where('tgl_antrian',date('Y-m-d'))->where('status',0)->count('id');
        $this->data['sisaantrian'] = ($nosisaantrian != 0) ? $nosisaantrian : 0;
        $this->data['noantriannow']= Loket::where('tgl_antrian',date('Y-m-d'))->where('status',0)->first();
        return view('Admin.loket.main')->with('data', $this->data);
    }

    public function getpasien(Request $request){
        $norm = Loket::where('no_antrian',$request->noantrian)->where('tgl_antrian',date('Y-m-d'))->first();
        $data = Customer::where('KodeCust',$norm->no_customer)->first();
        $return = ['status'=>'success','code'=>200,'data'=>$data];
        return response()->json($return);
    } 

    public function register(Request $request){
        $this->data['classtutup'] = ' sidebar-collapse';
        return view('Admin.register.main')->with('data', $this->data);
    }

    public function dataRegister(Request $request){
        // $data = Register::getjson($request);
        $data = Rsu_Register::getjson($request);
        return response()->json($data);
    }

    public function detailregister(Request $request){
        // $data['reg'] = Register::find($request->noreg);
        $data['reg'] = Rsu_Register::find($request->noreg);
        $content = view('Admin.register.detail',$data)->render();
        $return = ['status'=>'success','code'=>'200','content'=>$content];
        return response()->json($return);
    }

    public function deletereg(Request $request){
        // return $request->all();
        $cekRawatJalanObat = Rsu_RawatJalanObat::where('No_Register',$request->noreg)->first();
        if (empty($cekRawatJalanObat)) {
            $cekRawatJalanTindakan = Rsu_RawatJalanTindakan::where('No_Register',$request->noreg)->first();
            if (empty($cekRawatJalanTindakan)) {
                // $url = "https://dvlp.bpjs-kesehatan.go.id/vclaim-rest/SEP/Delete"; //url web service bpjs develop
                $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/SEP/Delete"; //url web service bpjs rilis
                $consID     = "21095"; //customer ID RS
                $secretKey  = "rsud6778ws122mjkrt"; //secretKey RS
                $method = 'DELETE';
                $port = '8080';

                $namauser = Auth::user()->name_user;
                $data =array(
                            "t_sep"=> [
                               "noSep"=> $request->nosep,   //
                               "user" => $namauser,
                               ],
                      );
                $params = json_encode(array('request' => $data));

                $result = Requestor::set_curl_bridging_new($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
                if ($result === false) { 
                   $return = ['status'=>'success','code'=>500, 'message'=>'Tidak Bisa Terhubung dengan Server BPJS, Silahkan Coba Beberapa Saat Lagi !'];
                } else { 
                    $delsep = json_decode($result, true); 

                    // $cekReg = Register::where('No_Register',$request->noreg)->first(); // db lokal
                    $cekReg = Rsu_Register::where('No_Register',$request->noreg)->first(); // db rsu
                    // $updateCC = CC::where('norm', $cekReg->No_RM)->where('tanggal', date('d-m-Y', strtotime($cekReg->Tgl_Register)))->first(); // db lokal
                    $updateCC = Rsu_cc::where('norm', $cekReg->No_RM)->where('tanggal', date('d-m-Y', strtotime($cekReg->Tgl_Register)))->first(); // db rsu
                    if (!empty($updateCC)) {
                        // $updateCC->KET = 'Blokir';
                        // $updateCC->save();
                        $kueri = 'UPDATE cc SET KET="Blokir" WHERE norm = "'.$cekReg->No_RM.'" AND tanggal = "'.date('d-m-Y', strtotime($cekReg->Tgl_Register)).'"';
                        DB::connection('dbrsudlain')->select($kueri);
                    }

                    // if($delsep['metaData']['code'] == 200){
                        // $jl = Rawatjalan::where('No_Register',$request->noreg)->delete();
                        // $reg = Register::where('No_Register',$request->noreg)->delete();
                        $jl = Rsu_Rawatjalan::where('No_Register',$request->noreg)->delete();
                        $reg = Rsu_Register::where('No_Register',$request->noreg)->delete();

                        if($jl && $reg){
                            $return = ['status'=>'success','code'=>200, 'message'=>'Data Pasien & SEP Berhasil di Hapus !'];
                        }else{
                            $return = ['status'=>'error','code'=>500, 'message'=> "Data Pasien Gagal di Hapus "];
                        }
                    // }else{
                       // $return = ['status'=>'error'.'00','code'=>$delsep['metaData']['code'], 'message'=>$delsep['metaData']['message']];
                    // }

                }
            }else{
                $return = ['status'=>'error','code'=>500, 'message'=> "Sudah Ada Transaksi, Data Tidak Bisa Dihapus !!"];
            }
        }else{
            $return = ['status'=>'error','code'=>500, 'message'=> "Sudah Ada Transaksi, Data Tidak Bisa Dihapus !!"];
        }
        return response()->json($return);
    }

}
