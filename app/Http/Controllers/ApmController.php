<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Libraries\compressFile;
use App\Http\Libraries\Formatters;
use App\Http\Models\Identity;
use App\Http\Models\Apm;
use App\Http\Models\Antrian;
use App\Http\Models\Setupall;
use App\Http\Models\CC;
use App\Http\Models\Register;
use App\Http\Models\Customer;
use App\Http\Models\Poli;
use App\Http\Models\Rsu_setupall;
use App\Http\Models\Rsu_Historypasien;
use App\Http\Models\Rsu_Register;
use App\Http\Models\Rsu_Tracer;
use App\Http\Models\rsu_poli;
use App\Http\Models\Rsu_Rawatjalan;
use App\Http\Models\rsu_customer;
use App\Http\Models\rsu_diagnosaBpjs;
use App\Http\Models\Rsu_cc;
use Redirect, Validator;

class ApmController extends Controller
{
    public function main(Request $request)
    {
        $id = $request->id;
        $this->data['getAntrian'] = Antrian::where('id', $id)->first();
        $this->data['classtutup'] = ' sidebar-collapse';
        // $this->data['poli'] = Poli::all();
        // $nosisaantrian = Loket::where('tgl_antrian',date('Y-m-d'))->where('status',0)->count('id');
        // $this->data['sisaantrian'] = ($nosisaantrian != 0) ? $nosisaantrian : 0;
        // $this->data['noantriannow']= Loket::where('tgl_antrian',date('Y-m-d'))->where('status',0)->first();
        // $this->data['jenispasien'] = Rsu_Setupall::where('groups','asuransi')->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])->get(); // db local
        // $this->data['jenispasien'] = Setupall::where('groups','asuransi')
        $this->data['jenispasien'] = Rsu_Setupall::where('groups','asuransi') // db rsu
                                            ->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])
                                            ->get(); // db rsu
        return view('Admin.Apm.main')->with('data', $this->data);
    }

    public function carifromantrianrs(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $content = view('Admin.Apm.carifromantrianrs')->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function cariAntrianApmrs(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $gopage = ($request->gopage) ? $request->gopage : 0;
        if ($request->gopage != 0) {
            $start = ($request->gopage - 1) * 15;
        }else{
            $start = ($request->start) ? $request->start : 0;
        }
        $end = ($request->end) ? $request->end : 15;
        // $dateNow = date('Y-m-d');
        // $data = CC::where('tanggal',date('d-m-Y')) // db loical
        $data = Rsu_cc::where('tanggal', date('d-m-Y')) // db rsu
                    ->where($request->kat,'like','%'.$request->key.'%')
                    // ->orderby('nourut','ASC')
                    ->orderby('status','ASC')
                    ->orderby('poli','ASC')
                    ->orderby('nourut','ASC')
                    ->offset($start)->limit($end)
                    ->get();
        // $sum = CC::where('tanggal',date('d-m-Y')) // db local
        $sum = Rsu_cc::where('tanggal',date('d-m-Y')) // db rsu
                    ->where($request->kat,'like','%'.$request->key.'%')
                    // ->orderby('nourut','ASC')
                    ->orderby('status','ASC')
                    ->orderby('poli','ASC')
                    ->orderby('nourut','ASC')
                    ->count();
        // $data = Apm::select('apm.*','tm_customer.FieldCust1','tm_poli.NamaPoli')
        //             ->join('tm_customer','tm_customer.KodeCust','=','apm.KodeCust')
        //             ->join('tm_poli','tm_poli.KodePoli','=','apm.KodePoli')
        //             ->orderby('no_antrian','ASC')
        //             ->where($request->kat,'like','%'.$request->key.'%')
        //             ->where('apm.tanggal',$dateNow)
        //             ->offset($start)->limit($end)
        //             ->get();
        // $sum = Apm::select('apm.*','tm_customer.FieldCust1','tm_poli.NamaPoli')
        //             ->join('tm_customer','tm_customer.KodeCust','=','apm.KodeCust')
        //             ->join('tm_poli','tm_poli.KodePoli','=','apm.KodePoli')
        //             ->orderby('no_antrian','ASC')
        //             ->where($request->kat,'like','%'.$request->key.'%')
        //             ->where('apm.tanggal',$dateNow)
        //             ->count();

        // $data = Customer::where($request->kat,'like','%'.$request->key.'%')->offset($start)->limit($end)->get();
        // $sum = Customer::where($request->kat,'like','%'.$request->key.'%')->count();

        $return = ['status'=>'success','code'=>200,'data'=>$data,'sum'=>$sum,'gopage'=>$gopage];
        return response()->json($return);
    }

    public function prosesAntrianUmum(Request $request)
    {
        // $infoCust = Customer::where('KodeCust',$request->key)->first(); // db local
        $infoCust = rsu_customer::where('KodeCust',$request->key)->first(); // db rsu
        if (!empty($infoCust)) {
            // $cekCC = CC::where('norm',$request->key)->where('tanggal',$request->tanggal)->where('nourut',$request->noUrut)->first(); // db local
            $cekCC = Rsu_cc::where('norm',$request->key)->where('tanggal',$request->tanggal)->where('nourut',$request->noUrut)->first();
            // $infoPoli = Poli::where('NamaPoli',$cekCC->poli)->first(); // db lokal
            $infoPoli = rsu_poli::where('NamaPoli',$cekCC->poli)->first(); // db rsu
            date_default_timezone_set('Asia/Jakarta');
            // $reg = new Register; //db lokal site
            $reg = new Rsu_Register; //db rsu
            $reg->TransReg = 'RE';
            $tg = date('y');
            $tg =$tg.'2';
            $thn = date('Y'); $mo = date('m'); $da = date('d');
            // $urut = Register::whereRaw("(YEAR(Tgl_Register) = $thn) AND LEFT(No_Register,3) = $tg ") // lokal
            $urut = Rsu_Register::whereRaw("(YEAR(Tgl_Register) = $thn) AND LEFT(No_Register,3) = $tg ") // db rsu
                                ->orderby('No_Register','DESC')
                                ->first(); //db rsu
            // $urut = Rsu_Register::whereYEAR('Tgl_Register','=',date('Y'))->select('No_Register','Tgl_Register')->orderby('No_Register','DESC')->first(); //db rsu
            // $urut = Register::whereYEAR('Tgl_Register','=',date('Y'))->select('No_Register','Tgl_Register')->orderby('No_Register','DESC')->first(); //db lokal site
            if($urut){
                $nourut = $urut->No_Register + 1;
            }else{
                $nourut = date('y').'20000001';
            }

            $reg->No_Register = $nourut;
            $reg->Tgl_Register = date('Y-m-d H:i:s');
            $reg->Jam_Register = date('H:i:s');
            $reg->No_RM =  $infoCust->KodeCust;
            $reg->Nama_Pasien = $infoCust->NamaCust;
            $reg->AlamatPasien = $infoCust->Alamat;
            $reg->Umur = $infoCust->umur;
            $reg->Kode_Ass = null;
            $reg->Kode_Poli1 = $infoPoli->KodePoli;
            $reg->JenisKel = $infoCust->JenisKel;
            $reg->NoSEP = null;
            $reg->NoPeserta = null;
            $reg->Biaya_Registrasi = 5000;
            $reg->Status = 'Belum Dibayar';
            $reg->NamaAsuransi = null;
            $reg->Japel = 3000;
            $reg->JRS = 2000;
            $reg->TipeReg = 'REG';
            $reg->SudahCetak = 'N';
            $reg->BayarPendaftaran = 'N';
            $reg->Tgl_Lahir = $infoCust->TglLahir;
            $reg->isKaryawan = 'N';
            $reg->isProcessed = 'N';
            $reg->isPasPulang = 'N';
            $reg->Jenazah     = 'N';
            $reg->save();

            if ($reg) {
                // $updateCC = CC::where('norm',$request->key)->where('tanggal',$request->tanggal)->where('nourut',$request->noUrut)->update(['status' => 'Sudah']); // db local
                // $updateCC = CC::where('norm',$request->key) // db lokal
                $updateCC = Rsu_cc::where('norm',$request->key) // db rsu
                                    ->where('tanggal',$request->tanggal)
                                    ->where('nourut',$request->noUrut)
                                    ->update(['status' => 'Sudah']);

                $return = ['status'=>'success','code'=>200,'messages'=>'Registrasi Berhasil Dilakukan !!'];
            }else{
                $return = ['status'=>'error','code'=>500,'messages'=>'Registrasi Gagal Dilakukan !!'];
            }
        }else{
            $return = ['status'=>'error','code'=>500,'messages'=>'Pasien Tidak Ditemukan !!'];
        }
        return response()->json($return);
    }
}
