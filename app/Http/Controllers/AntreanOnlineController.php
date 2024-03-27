<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Antrian;
use App\Http\Models\MstKonterPoli;
use App\Http\Models\TransKonterPoli;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Http\Models\rsu_poli;
use Datatables, DB, Auth,DateTime;
// use Redirect, Validator, Datatables, DB, Auth, DateTime;

class AntreanOnlineController extends Controller
{
    public function main(){
        $data['user'] = Auth::user()->id;
        
        if ($data['user'] != 1) {
            $data['dataDropdownCounter'] = MstKonterPoli::where('user_id', $data['user'])->get();
        } else {
            $data['dataDropdownCounter'] = MstKonterPoli::all();
        }
        return view('Admin.antreanBPJS.antreanOnline.main', $data);
    }

    public function datagrid(Request $request)
    {
        if ($request->ajax()) {
            $today = date('Y-m-d');
            $namaCounter = $request->namaCounter;
            $counter = $request->namaCounter;
            $botDaPas = DB::connection('mysql')->table('bot_data_pasien as bdp')
                ->join('bot_pasien as bp','bdp.idBots','=','bp.id')
                ->selectRaw("bdp.*, IF(bdp.cust_id IS NULL,'','WA') as dari")
                ->where('bp.statusChat','99')
                ->where('is_pasien_baru', 0)
                ->where('caraBayar', 'BPJS')
                ->whereBetween('tglBerobat',[$request->tglAwal,$request->tglAkhir])
                ->get();

            $simapan = DB::connection('mysql')->table('pasien_baru_temporary')
                ->whereBetween('tanggalPeriksa',[$request->tglAwal,$request->tglAkhir])
                ->where('isPasienBaru', 'N')
                ->where('caraBayar', 'BPJS')
                ->get();

            if(count($simapan)>0){
                foreach($simapan as $key => $val){
                    $val->dari = 'SIMAPAN';
                    array_push($botDaPas,$val);
                }
            }

            //modifi data
            foreach ($botDaPas as $key => $v) {
                if ($v->dari == 'WA') {
                    $getKdPoli = Rsu_Bridgingpoli::where('kdpoli', $v->kodePoli)->first();
                    $kdpoli_rs = $getKdPoli->kdpoli_rs;
                } else {
                    $kdpoli_rs = $v->kodePoli;
                }

                $idPoli = TransKonterPoli::where('poli_id', $kdpoli_rs)->first();
                $namaCounter = "-";
                if(!empty($idPoli)){
                    $counterPoli = MstKonterPoli::where('id', $idPoli->konter_poli_id)->first();
                    $namaCounter = $counterPoli->nama_konterpoli;
                }

                $v->counter_poli = $namaCounter;
                if(!empty($counter) && $v->counter_poli != $counter){
                    unset($botDaPas[$key]);
                }
            }

            return Datatables::of(collect($botDaPas))
            ->addIndexColumn()
            ->addColumn('tanggal_lahir',function($row){
                if($row->dari == 'WA'){
                    // return  date('Y-m-d', strtotime($row->tglLahir));

                    $tanggal = new DateTime($row->tglLahir);
                    $today = new DateTime('today');
                    $y = $today->diff($tanggal)->y;
                    $m = $today->diff($tanggal)->m;
                    $d = $today->diff($tanggal)->d;
                    if ($y > 65)  {
                        $btn = "<button disabled class='btn btn-sm btn-danger'>".$y . " tahun " . $m . " bulan " . $d . " hari"."</button>";
                        return $btn;
                    }elseif ($y < 17) {
                        $btn = "<button disabled class='btn btn-sm btn-danger'>".$y . " tahun " . $m . " bulan " . $d . " hari"."</button>";
                        return $btn;
                    }else{
                        $btn = "<button disabled class='btn btn-sm btn-success'>".$y . " tahun " . $m . " bulan " . $d . " hari"."</button>";
                        return $btn;
                    }
                } else {
                    // return  date('Y-m-d', strtotime($row->tanggalLahir));

                    $tanggal = new DateTime($row->tanggalLahir);
                    $today = new DateTime('today');
                    $y = $today->diff($tanggal)->y;
                    $m = $today->diff($tanggal)->m;
                    $d = $today->diff($tanggal)->d;
                    if ($y > 65)  {
                        $btn = "<button disabled class='btn btn-sm btn-danger'>".$y . " tahun " . $m . " bulan " . $d . " hari"."</button>";
                        return $btn;
                    }elseif ($y < 17) {
                        $btn = "<button disabled class='btn btn-sm btn-danger'>".$y . " tahun " . $m . " bulan " . $d . " hari"."</button>";
                        return $btn;
                    }else{
                        $btn = "<button disabled class='btn btn-sm btn-success'>".$y . " tahun " . $m . " bulan " . $d . " hari"."</button>";
                        return $btn;
                    }
                }
            })
            ->addColumn('poli',function($row){
                if($row->dari == 'WA'){
                    $poliTujuan = Rsu_Bridgingpoli::with('tm_poli')->where('kdpoli', $row->kodePoli)->first();
                    return  $poliTujuan->tm_poli->NamaPoli;
                } else {
                    $poliTujuan = Rsu_Bridgingpoli::with('tm_poli')->where('kdpoli_rs', $row->kodePoli)->first();
                    return  $poliTujuan->tm_poli->NamaPoli;
                }
            })
            ->addColumn('alamatPasien',function($row){
                if($row->dari == 'WA'){
                    return  $row->alamat;
                } else {
                    return  '';
                }
            })
            ->addColumn('metode',function($row){
                if($row->dari == 'WA'){
                    return  'WA';
                } else {
                    return  'SIMAPAN';
                }
            })
            ->addColumn('geriatri',function($row){
                return $row->isGeriatri;
            })
            ->addColumn('noRm',function($row){
                if($row->dari == 'WA'){
                    return  $row->KodeCust;
                } else {
                    return  $row->no_rm;
                }
            })
            ->addColumn('buatSep',function($row){
                if(!empty($row->sudah_buat_sep)){
                    return  "Sudah";
                } else {
                    return  '-';
                }
            })
            ->addColumn('cetakSep',function($row){
                if(!empty($row->sudah_cetak_sep)){
                    return  "Sudah";
                } else {
                    return  '-';
                }
            })
            ->addColumn('action',function($row){
                $metode = $row->dari;
                if ($row->dari == 'WA') {
                    $id = $row->cust_id;
                } else {
                    $id = $row->id_pas;
                }
                
                $params = [$id,$metode];
                $param = json_encode($params);
                $btn = "<button class='btn btn-sm btn-success' title='Buat SEP' onclick='buatSep(`$param`)'><i class='fa fa-file' aria-hidden='true'></i></button>";
                return $btn;
            })
            ->make(true);
        }
        return view('Admin.antreanBPJS.antreanOnline.main');
    }
}