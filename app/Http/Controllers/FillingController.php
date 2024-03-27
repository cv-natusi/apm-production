<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Models\Rsu_Bridgingpoli;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Antrian;
use App\Http\Models\Filling;
use DB;
use Illuminate\Support\Facades\Log;
use Yajra\Datatables\Facades\Datatables;

class FillingController extends Controller{
    public function main(){
        return view('Admin.filling.main');
    }
    
    public function dashboard(){
        return view('Admin.filling.dashboard');
    }

    public function cariFilling(Request $req){
        $rm = $req->rm;
        $date = date("Y-m-d");
        $filling = Filling::where('no_rm','LIKE',"$rm%")
            ->where('tgl_periksa',$date)
            ->limit(2)->get();
        if(count($filling)>0){
            return ['status'=>'success','message'=>'data ditemukan','code'=>200,'data'=>$filling];
        }else{
            return ['status'=>'error','message'=>'data tidak ditemukan','code'=>400,'data'=>''];
        }
    }

    public function changeStatFilling(Request $request){
        try {
            $idFilling = $request->idFilling;
            $status = $request->status;
            $filling = Filling::find($idFilling);
            if(empty($filling)){
                return ['status' => 'error','code' => 500, 'message' => 'Data Filling Tidak Ditemukan'];
            }
            
            $filling->status = $status;
            $filling->tgl_filling = date('Y-m-d H:i:s');
            $filling->save();
            
            $logs = [
                'user' => \Auth::User()->email,
                'filling' => $filling
            ];
            Log::info('Filling Controller - changeStatFilling :', $logs);
            return ['status' => 'success','code' => 200, 'message' => 'Berhasil Mengganti Status Filling'];

        } catch (\Exception $th) {
            $logs = [
                'user' => \Auth::User()->email,
                'error' => $th->getMessage()
            ];
            Log::info('Filling Controller - changeStatFilling - error :', $logs);
            return ['status' => 'error','code' => 500, 'message' => 'Kesalahan Ketika Mengupdate Data, Silahkan Coba Lagi', 'messageErr' => $th->getMessage()];
        }
    }

    public function dataGridFilling(Request $request){
        $filternya = $request->type;
        $search = $request->filterNama;
        $date = date("Y-m-d");
        // $date = date("Y-m-d",strtotime('31-01-2023'));

        if($filternya == "no_rm"){
            $filling = Filling::where('no_rm', 'like', "%". $search ."%")
            ->where('tgl_periksa',$date)
            ->get();
        }else{
            $filling = Filling::with([
                'customer'
                // 'customer' => function($q) use($search) {
                //     $q->where('NamaCust', 'like', "%". $search ."%");
                // }
            ])
            ->where('tgl_periksa',$date)
            ->get();
        }

        //modify data
        foreach ($filling as $key => $value) {
            if($filternya == "nama_pasien"){
                if(empty($value->customer)){
                    unset($filling[$key]);
                }
                $data = DB::connection('dbrsud')->table('tm_customer')
                        ->where('KodeCust', $value->no_rm)
                        ->first();
            }
            $antrian = DB::connection('mysql')->table('antrian')
                    ->where('id', $value->antrian_id)
                    ->first();
            $wa = DB::connection('mysql')->table('bot_data_pasien')
                    ->where('KodeCust', $value->no_rm)
                    ->where('tglBerobat',$value->tgl_periksa)
                    ->first();
            $kodePoli = !empty($antrian) ? $antrian->kode_poli : (!empty($wa) ? $wa->kodePoli : '');
            $nmPoli = ($kodePoli !='') ? Rsu_Bridgingpoli::join('tm_poli', 'mapping_poli_bridging.kdpoli_rs', '=', 'tm_poli.KodePoli')->where('kdpoli',$kodePoli)->first() : '-';
            $value->kodePolis = $kodePoli;
            $value->dari = (!empty($antrian) ? $antrian->metode_ambil : (!empty($wa) ? 'WA' : '-'));
            $value->namaPoli = ($nmPoli!='-') ? $nmPoli->NamaPoli : '-' ; 
            $value->customer = !empty($data) ? $data : [];
            $value->antrian = !empty($antrian) ? $antrian : [];
        }
        // return $filling;

        return Datatables::of(collect($filling))
            ->addIndexColumn()
            ->addColumn('statusnya',function($filling){
                return $this->templateStatus($filling);
            })
            ->make(true);
    }

    function templateStatus($data){
        $btn = "";
        //setting color
        // if($data->status == "belum"){
        //  $btn .= '<select id="'.$data->id.'" data-init="'.$data->status.'" onchange="changeStatFilling('.$data->id.')" class="form-control btn" style="color:white;background-color:black">';
        //  $btn .= '<option value="belum" selected class="btn" style="color:white;background-color:black">Pilih</option>';
        //  $btn .= '<option value="dicari" class="btn btn-warning">C</option>';
        //  $btn .= '<option value="kosong" class="btn" style="color:white;background-color:grey">T</option>';
        //  $btn .= '<option value="keluar" class="btn btn-danger">K</option>';
        //  $btn .= '<option value="ada" class="btn btn-success">R</option>';
        // }else
        // if( $data->status == "dicari" ){
        //     $btn .= '<select id="'.$data->id.'" data-init="'.$data->status.'" onchange="changeStatFilling('.$data->id.')" class="form-control btn btn-warning">';
        //     $btn .= '<option value="dicari" selected class="btn btn-warning">C</option>';
        //     $btn .= '<option value="kosong" class="btn" style="color:white;background-color:grey">T</option>';
        //     // $btn .= '<option value="keluar" class="btn btn-danger">K</option>';
        //     $btn .= '<option value="ada" class="btn btn-success">R</option>';
        // }

        if( $data->status == "kosong" ){
            $btn .= '<select id="'.$data->id.'" data-init="'.$data->status.'" onchange="changeStatFilling('.$data->id.')" class="form-control btn" style="color:white;background-color:grey">';
            $btn .= '<option value="dicari" class="btn btn-warning">C</option>';
            $btn .= '<option value="kosong" selected class="btn" style="color:white;background-color:grey">T</option>';
            // $btn .= '<option value="keluar" class="btn btn-danger">K</option>';
            $btn .= '<option value="ada" class="btn btn-success">R</option>';
        }
        // elseif( $data->status == "keluar" ){
        //  $btn .= '<select id="'.$data->id.'" data-init="'.$data->status.'" onchange="changeStatFilling('.$data->id.')" class="form-control btn btn-danger">';
        //  $btn .= '<option value="dicari" class="btn btn-warning">C</option>';
        //  $btn .= '<option value="kosong" class="btn" style="color:white;background-color:grey">T</option>';
        //  $btn .= '<option value="keluar" selected class="btn btn-danger">K</option>';
        //  $btn .= '<option value="ada" class="btn btn-success">R</option>';
        // }
        elseif( $data->status == "ada" ){
            $btn .= '<select id="'.$data->id.'" data-init="'.$data->status.'" onchange="changeStatFilling('.$data->id.')" class="form-control btn btn-success">';
            $btn .= '<option value="dicari" class="btn btn-warning">C</option>';
            $btn .= '<option value="kosong" class="btn" style="color:white;background-color:grey">T</option>';
            // $btn .= '<option value="keluar" class="btn btn-danger">K</option>';
            $btn .= '<option value="ada" selected class="btn btn-success">R</option>';
        }else{
            $btn .= '<select id="'.$data->id.'" data-init="'.$data->status.'" onchange="changeStatFilling('.$data->id.')" class="form-control btn btn-warning">';
            $btn .= '<option value="dicari" selected class="btn btn-warning">C</option>';
            $btn .= '<option value="kosong" class="btn" style="color:white;background-color:grey">T</option>';
            // $btn .= '<option value="keluar" class="btn btn-danger">K</option>';
            $btn .= '<option value="ada" class="btn btn-success">R</option>';
        }
        return $btn;
    }
}