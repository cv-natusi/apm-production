<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ApiSiramaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function panggil(Request $request){
        try {
            DB::beginTransaction();
            //masih abu2 punya sirama
            $kode_unit = isset($request->kode_unit) ? $request->kode_unit : "";
            $id_antrean = $request->id_antrean;
            //initial data
            $antrian = DB::connection('mysql')->table('antrian')
                            ->where('id', $id_antrean)
                            ->first();
            //getcode counter
            $codeCounter = DB::connection('mysql')->table('mapping_poli_bridging as mpb')
                ->join('trans_konter_poli as tkp','tkp.poli_id','=','mpb.kdpoli_rs' )
                ->join('mst_konterpoli as mk', 'mk.id','=','tkp.konter_poli_id')
                ->where('mpb.kdpoli', $antrian->kode_poli)
                ->first();
            //update status antrian ke panggilpoli
            $update =  DB::connection('mysql')->table('antrian')
                ->where('id', $id_antrean)
                ->where('status', "antripoli")
                ->update(['status' => "panggilpoli"]);
            if($update){
                //jika berhasil update maka insert ke pemanggilan dan update status di antrian tracer jadi 2
                $antrian_tracer = DB::connection('mysql')->table('antrian_tracer')
                            ->where('antrian_id', $id_antrean)
                            ->where('from','counter')
                            ->where('to','poli')
                            ->update([
                                'status_tracer' => "2", 
                                'time2' => date('H:i:s')
                            ]);
                $dataPemanggilan = [
                    'antrian_id' => $antrian->id,
                    'no_antrian' => $antrian->nomor_antrian_poli,
                    'status' => 1,
                    'dari' => $codeCounter->nama_konterpoli
                ];
                $pemanggilan = DB::connection('mysql')->table('pemanggilan')
                            ->insert([$dataPemanggilan]);
                DB::commit();
                return [
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Pasien dengan Nomor Antrian '.$antrian->nomor_antrian_poli.' berhasil dipanggil',
                    "data" => "if"
                ];
            }else{
                //jika gagal update maka data sudah ada, cukup update status dipemanggilan untuk recall pasien
                $pemanggilan = DB::connection('mysql')->table('pemanggilan')
                            ->where('antrian_id',$antrian->id)
                            ->where('no_antrian',$antrian->nomor_antrian_poli)
                            ->update([
                                'status' => 1
                            ]);
                DB::commit();
                return [
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Pasien dengan Nomor Antrian '.$antrian->nomor_antrian_poli.' berhasil dipanggil ulang',
                    "data"=>"else"
                ];
            }   
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'status'=>'error',
                'code'=>500,
                'message'=>'Gagal Memanggil Pasien Coba Ulangi Kembali',
                'messageerr'=>$e->getMessage(),
            ];
        }
    }

}
