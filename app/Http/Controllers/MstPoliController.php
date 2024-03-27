<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Http\Models\rsu_poli;
use App\Http\Models\Poli;
use App\Http\Models\KodeAwalanPoli;
use Redirect, Validator, Datatables, DB, Auth, DateTime;

class MstPoliController extends Controller{
    public function index(Request $request){
        return view('Admin.mstpoli.main');
    }

    public function listMstPoli(Request $request){
        if ($request->ajax()) {
            $data = Rsu_Bridgingpoli::has('tm_poli')
                ->with('tm_poli.kode_awalan_poli')
                ->groupBy('mapping_poli_bridging.kdpoli_rs')
                ->orderBy('kdpoli_rs','asc')
                ->get();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('kode',function($row){
                $kode = $row->tm_poli->kode_awalan_poli;
                $kode = !empty($kode)?$kode->kode_awal:'-';
                return $kode;
            })
            ->make(true);
        }
        return view('Admin.mstpoli.main');
    }

    public function form(Request $request){
        if (!empty($request->id)) {
            $data['data'] = Rsu_Bridgingpoli::has('tm_poli')
                ->with('tm_poli.kode_awalan_poli')
                ->groupBy('mapping_poli_bridging.kdpoli_rs')
                ->where('mapping_poli_bridging.kdpoli_rs', $request->id)
                ->first();
        }

        $data['poli'] = Rsu_Bridgingpoli::has('tm_poli')
            ->with('tm_poli')
            ->groupBy('mapping_poli_bridging.kdpoli_rs')
            ->orderBy('kdpoli_rs','asc')
            ->get();
        
        $content = view('Admin.mstpoli.form', $data)->render();
        return ['status' => 'success', 'content' => $content, 'data' => $data];
    }

    public function store(Request $request){
        $kodeAwalan = KodeAwalanPoli::where('kdpoli_rs', $request->id)->first();
        $cek = 'lama';
        if (empty($kodeAwalan)) {
            $cek = 'baru';
            $kodeAwalan = new KodeAwalanPoli;
        }
        $getKdPoli = Rsu_Bridgingpoli::where('kdpoli_rs', $request->poli)->first();

        $kodeAwalan->kdpoli    = strtoupper($getKdPoli->kdpoli);
        $kodeAwalan->kdpoli_rs = strtoupper($request->poli);
        $kodeAwalan->kode_awal = strtoupper($request->kodeawalan);
        $kodeAwalan->save();
        if($kodeAwalan){
            return [
                'status' => 'success',
                'code'   => 200,
                'message'   => ($cek=='baru'?'Data berhasil disimpan':'Data berhasil diperbarui'),
            ];
        }else{
            return [
                'status' => 'error',
                'code'   => 400,
                'message'   => ($cek=='baru'?'Data gagal disimpan':'Data gagal diperbarui'),
            ];
        }
    }
}