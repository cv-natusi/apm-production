<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Antrian;
use App\Http\Models\MstKonterPoli;
use App\Http\Models\TransKonterPoli;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

class RiwayatAntrianController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $dataDropdownCounter = MstKonterPoli::all();

        return view('Admin.riwayatantrian.main', compact('dataDropdownCounter'));
    }

    public function getRiwayatAntrian(Request $request){
        $namaCounter = $request->namaCounter;
        $counter = $request->namaCounter;
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;

        if ($request->ajax()) {
            $today = date('Y-m-d');
            $data = Antrian::with(['tm_customer','mapping_poli_bridging.tm_poli'])
                ->whereNotIn('status', ['batal', 'antrifarmasi', 'panggilfarmasi'])
                ->whereBetween('tgl_periksa', [$tglAwal, $tglAkhir])
                ->orderBy('id','asc')
                ->get();

            //modifi data
            foreach ($data as $key => $v) {
                // $antrian_tracer = DB::connection('mysql')->table('antrian_tracer')
                //     ->where('antrian_id',$v->id)
                //     ->whereIn('from', ['loket','simapan','kiosk','wa'])
                //     ->orderBy('time', 'ASC')
                //     ->first();
                // $selesaiDilayani = DB::connection('mysql')->table('antrian_tracer')
                //     ->where('antrian_id',$v->id)
                //     ->whereIn('to', ['loket', 'poli', 'counter'])
                //     ->first();

                $idPoli = TransKonterPoli::where('poli_id', $v->mapping_poli_bridging->kdpoli_rs)->first();
                $namaCounter = "-";
                if(!empty($idPoli)){
                    $counterPoli = MstKonterPoli::where('id', $idPoli->konter_poli_id)->first();
                    $namaCounter = $counterPoli->nama_konterpoli;
                }

                $selesai_dilayani = empty($selesaiDilayani) ? "-" : $selesaiDilayani->time2;
                // $v->antrian_tracer = $antrian_tracer;
                // $v->selesai_dilayani = empty($selesai_dilayani) ? "-" : $selesai_dilayani;
                $v->counter_poli = $namaCounter;

                if(!empty($counter) && $v->counter_poli != $counter){
                    unset($data[$key]);
                }
            }

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action',function($row){
                $a = $this->templateAction($row);
                return $a;
            })
            ->addColumn('RM',function($row){
                $dt = ($row->no_rm=='00000000000')? '-' : $row->no_rm;
                $txt = '<p style="color:black" class="text-center">'.$dt.'</p>';
                return $txt;
            })
            ->addColumn('NamaCust',function($row){
                $dt = isset($row->tm_customer)? $row->tm_customer->NamaCust : '-';
                $txt = '<p style="color:black" class="text-center">'.$dt.'</p>';
                return $txt;
            })
            ->addColumn('noAntrian',function($row){
                if (!empty($row->no_antrian_pbaru)) {
                    $txt = '<span style="color:black" class="text-center">'.$row->no_antrian.'&nbsp; <button class="btn btn-sm btn-danger">'.$row->no_antrian_pbaru.'</button></span>';
                } else {
                    $txt = '<span style="color:black" class="text-center">'.$row->no_antrian.'</span>';
                }
                return $txt;
            })
            ->make(true);
        }

        return view('Admin.antreanBPJS.listAntrian.mainLoket');
    }

    // public function getRiwayatAntrian(Request $request){
    //     $namaCounter = $request->namaCounter;
    //     $counter = $request->namaCounter;
    //     $tglAwal = $request->tglAwal;
    //     $tglAkhir = $request->tglAkhir;

    //     if ($request->ajax()) {
    //         $today = date('Y-m-d');
    //         $data = Antrian::select(['id','kode_booking','no_rm','no_antrian', 'nomor_antrian_poli', 'tgl_periksa', 'metode_ambil', 'jenis_pasien', 'kode_poli'])
    //             ->with(['tm_customer','mapping_poli_bridging.tm_poli'])
    //             ->whereNotIn('status', ['batal', 'antrifarmasi', 'panggilfarmasi'])
    //             ->whereBetween('tgl_periksa', [$tglAwal, $tglAkhir])
    //             ->orderBy('created_at','ASC')
    //             ->get();

    //         //modifi data
    //         foreach ($data as $key => $v) {
    //             // $antrian_tracer = DB::connection('mysql')->table('antrian_tracer')
    //             //     ->where('antrian_id',$v->id)
    //             //     ->whereIn('from', ['loket','simapan','kiosk','wa'])
    //             //     ->orderBy('time', 'ASC')
    //             //     ->first();
    //             // $selesaiDilayani = DB::connection('mysql')->table('antrian_tracer')
    //             //     ->where('antrian_id',$v->id)
    //             //     ->whereIn('to', ['loket', 'poli', 'counter'])
    //             //     ->first();

    //             $idPoli = TransKonterPoli::where('poli_id', $v->mapping_poli_bridging->kdpoli_rs)->first();
    //             $namaCounter = "-";
    //             if(!empty($idPoli)){
    //                 $counterPoli = MstKonterPoli::where('id', $idPoli->konter_poli_id)->first();
    //                 $namaCounter = $counterPoli->nama_konterpoli;
    //             }

    //             // $selesai_dilayani = empty($selesaiDilayani) ? "-" : $selesaiDilayani->time2;
    //             // $v->antrian_tracer = $antrian_tracer;
    //             // $v->selesai_dilayani = empty($selesai_dilayani) ? "-" : $selesai_dilayani;
    //             $v->counter_poli = $namaCounter;

    //             if(!empty($counter) && $v->counter_poli != $counter){
    //                 unset($data[$key]);
    //             }
    //         }

    //         return Datatables::of($data)
    //         ->addIndexColumn()
    //         ->addColumn('action',function($row){
    //             $a = $this->templateAction($row);
    //             return $a;
    //         })
    //         ->make(true);
    //     }

    //     return view('Admin.antreanBPJS.listAntrian.mainLoket');
    // }

    function templateAction($data){
		$btn = "<div class='text-center'>";
		$btn .= "<button class='btn btn-sm btn-primary' title='Detail' onclick='detail(`$data->id`)'><i class='fa fa-eye' aria-hidden='true'></i></button> &nbsp;";
		$btn .= "</div>";

		return $btn;
	}
}