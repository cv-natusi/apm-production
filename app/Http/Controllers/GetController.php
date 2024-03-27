<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Provinsi;
use App\Http\Models\Kabupaten;
use App\Http\Models\Kecamatan;
use App\Http\Models\Desa;
use File, Auth, Redirect, Validator, DB;

class GetController extends Controller{
    function getKabupaten(Request $request){
    //   return $request->all();
      $id_provinsi = $request->id;
      $kabupaten = Kabupaten::where('provinsi_id',$id_provinsi)->get();
      
      if(count($kabupaten)!=0){
        $return = [
          'status'=>'success',
          'message'=>'Data ditemukan',
          'data'=>$kabupaten,
        ];
      }else{
        $return = [
          'status'=>'error',
          'message'=>'Data tidak ditemukan',
          'data'=>[],
        ];
      }
      return $return;
    }

    function getKecamatan(Request $request){
      $id_kabupaten = $request->id;
      $kecamatan = Kecamatan::where('kabupaten_id',$id_kabupaten)->get();

      if(count($kecamatan)!=0){
        $return = [
          'status'=>'success',
          'message'=>'Data ditemukan',
          'data'=>$kecamatan,
        ];
      }else{
        $return = [
          'status'=>'error',
          'message'=>'Data tidak ditemukan',
          'data'=>[],
        ];
      }
      return $return;
    }

    function getDesa(Request $request){
      $id_kecamatan = $request->id;
      $desa = Desa::where('kecamatan_id',$id_kecamatan)->get();

      if(count($desa)!=0){
        $return = [
          'status'=>'success',
          'message'=>'Data ditemukan',
          'data'=>$desa,
        ];
      }else{
        $return = [
          'status'=>'error',
          'message'=>'Data tidak ditemukan',
          'data'=>[],
        ];
      }
      return $return;
    }
    function jadwalOperasiPasien(Request $request){

        $nopeserta = $request->nopeserta;
        if (strlen($nopeserta) <> 13) {
          return [
            'metadata'=> [
              'message'=> 'No kartu tidak valid atau kurang dari 13 digit',
              'code'=> 502
            ]
          ];
        }
  
        $operasi = DB::connection('dbrsudlain')->table('tm_operasi')->where('nobpjs',$nopeserta)->where('status','0')->get();
  
        $list = [];
        if($operasi->count()!=0){
          foreach ($operasi as $key) {
            $data = [
              'kodebooking'=> $key->id_operasi,
              'tanggaloperasi'=> $key->tanggal,
              'jenistindakan'=> $key->jenis_tindakan,
              'kodepoli'=> $key->Kode_ruangan,
              'namapoli'=> $key->Ruangan,
              'terlaksana'=> $key->status
            ];
  
            array_push($list,$data);
          }
          return [
            'response'=> [
              'list' => $list,
            ],
            'metadata'=> [
              'message'=> 'Ok',
              'code'=> 200
            ]
          ];
        }else{
          return [
            'response'=> [
              'list' => $list,
            ],
            'metadata'=> [
              'message'=> 'Tidak ada data',
              'code'=> 250
            ]
          ];
        }
    }
  
    function jadwalOperasiRS(Request $request){
        $tanggalawal    = $request->tanggalawal;
        $tanggalakhir   = $request->tanggalakhir;
  
        if (empty($tanggalawal)) {
          return [
            'metadata'=> [
              'message'=> 'Tanggal Awal tidak boleh kosong.',
              'code'=> 250
            ]
          ];
        }
  
        if (empty($tanggalakhir)) {
          return [
            'metadata'=> [
              'message'=> 'Tanggal Akhir tidak boleh kosong.',
              'code'=> 250
            ]
          ];
        }
  
        if ($tanggalawal > $tanggalakhir ) {
          return [
            'metadata'=> [
              'message'=> 'Tanggal Awal tidak boleh lebih dari Tanggal Akhir.',
              'code'=> 250
            ]
          ];
        }
  
        if ($tanggalakhir < $tanggalawal) {
          return [
            'metadata'=> [
              'message'=> 'Tanggal Akhir tidak boleh kurang dari Tanggal Awal.',
              'code'=> 250
            ]
          ];
        }
  
        $operasi = DB::connection('dbrsudlain')->table('tm_operasi')->where('tanggal','>=',$tanggalawal)->where('tanggal','<=',$tanggalakhir)->get();
  
        $list = [];
        if(!empty($operasi)){
          foreach ($operasi as $key) {
            $data = [
              'kodebooking'=> $key->id_operasi,
              'tanggaloperasi'=> $key->tanggal,
              'jenistindakan'=> $key->jenis_tindakan,
              'kodepoli'=> $key->Kode_ruangan,
              'namapoli'=> $key->Ruangan,
              'terlaksana'=> $key->status,
              'nopeserta'=> $key->nobpjs,
              'lastupdate'=> strtotime(date('Y-m-d'))
            ];
  
            array_push($list,$data);
          }
          return [
            'response'=> [
              'list' => $list,
            ],
            'metadata'=> [
              'message'=> 'Ok',
              'code'=> 200
            ]
          ];
        }else{
          return [
            'response'=> [
              'list' => $list,
            ],
            'metadata'=> [
              'message'=> 'Data tidak ditemukan',
              'code'=> 250
            ]
          ];
        }
    }
}
