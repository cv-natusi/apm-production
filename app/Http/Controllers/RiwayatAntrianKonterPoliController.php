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
use Auth;

class RiwayatAntrianKonterPoliController extends Controller
{
    public function main() {
        $data['user'] = Auth::user()->id;
        
        if ($data['user'] != 1) {
            $data['dataDropdownCounter'] = MstKonterPoli::where('user_id', $data['user'])->get();
        } else {
            $data['dataDropdownCounter'] = MstKonterPoli::all();
        }

        return view('Admin.riwayatantrian.konterPoli', $data);    
    }

    public function riwayatAntrianKonterPoli(Request $request) {
        $namaCounter = $request->namaCounter;
        $counter = $request->namaCounter;
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;

        if ($request->ajax()) {
            $today = date('Y-m-d');
            $data = Antrian::select(['id','no_rm','no_antrian', 'nomor_antrian_poli', 'tgl_periksa', 'metode_ambil', 'jenis_pasien', 'kode_poli'])
                ->with(['tm_customer','mapping_poli_bridging.tm_poli'])
                ->whereNotIn('status', ['batal'])
                ->whereBetween('tgl_periksa', [$tglAwal, $tglAkhir])
                ->orderBy('no_antrian','ASC')
                ->get();

            // $data = Antrian::with(['tm_customer','mapping_poli_bridging.tm_poli'])
            //     ->whereNotIn('status', ['batal', 'antrifarmasi', 'panggilfarmasi'])
            //     ->whereBetween('tgl_periksa', [$tglAwal, $tglAkhir])
            //     ->orderBy('no_antrian','ASC')
            //     ->get();

            // //modifi data
            foreach ($data as $key => $v) {
            //     $antrian_tracer = DB::connection('mysql')->table('antrian_tracer')
            //         ->where('antrian_id',$v->id)
            //         ->where('to', 'counter')
            //         ->orderBy('time', 'ASC')
            //         ->first();
            //     $selesaiDilayani = DB::connection('mysql')->table('antrian_tracer')
            //         ->where('antrian_id',$v->id)
            //         ->where('to', 'poli')
            //         ->first();

                $idPoli = TransKonterPoli::where('poli_id', $v->mapping_poli_bridging->kdpoli_rs)->first();
                $namaCounter = "-";
                if(!empty($idPoli)){
                    $counterPoli = MstKonterPoli::where('id', $idPoli->konter_poli_id)->first();
                    $namaCounter = $counterPoli->nama_konterpoli;
                }

            //     $selesai_dilayani = empty($selesaiDilayani) ? "-" : $selesaiDilayani->time2;
            //     $v->antrian_tracer = $antrian_tracer;
            //     $v->selesai_dilayani = empty($selesai_dilayani) ? "-" : $selesai_dilayani;
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
            ->addColumn('nomor_antrian_poli',function($row){
                $str = ($row->nomor_antrian_poli) ? $row->nomor_antrian_poli : '-';
                $txt = '<p style="color:black" class="text-center">'.$str.'</p>';
                return $txt;
            })
            ->addColumn('namaCust',function($row){
                $nama = isset($row->tm_customer) ? $row->tm_customer->NamaCust : '-';
                $txt = '<p style="color:black" class="text-center">'.$nama.'</p>';
                return $txt;
            })
            ->make(true);
        }

        return view('Admin.riwayatantrian.konterPoli');
    }

    function templateAction($data) {
        $btn = "<div class='text-center'>";
        $btn .= "<button class='btn btn-sm btn-primary' title='Detail' onclick='detail(`$data->id`)'><i class='fa fa-eye' aria-hidden='true'></i></button> <br>";
        $btn .= "<button class='btn btn-sm btn-warning' style='margin-left: 5px; margin-top: 5px;' title='Cetak No Antrian' onclick='cetakNoAntri(`$data->id`)'><i class='fa fa-print' aria-hidden='true'></i></button> &nbsp;";
        $btn .= "<button class='btn btn-sm btn-success' style='margin-left: 0px; margin-top:5px;' title='Buat SEP' onclick='buatSep(`$data->id`)'><i class='fa fa-file-text' aria-hidden='true'></i></button> &nbsp;";
        $btn .= "</div>";

        return $btn;
    }
}