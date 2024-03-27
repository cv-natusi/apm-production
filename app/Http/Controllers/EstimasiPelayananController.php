<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\EstimasiPelayanan;
use App\Http\Models\rsu_poli;
use File, Auth, Redirect, Validator;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Environment\Console;

class EstimasiPelayananController extends Controller
{
    public function main(Request $request)
    {
        $this->data['classtutup'] = '';
        return view('Admin.EstimasiPelayanan.main')->with('data', $this->data);
    }

    public function datagrid(Request $request)
    {
        $data = EstimasiPelayanan::getJson($request);
        return response()->json($data);
    }

    public function formAdd(Request $request)
    {
        $this->data['tm_poli'] = DB::table('tm_poli')->get();
        $content = view('Admin.EstimasiPelayanan.formAdd')->with('data', $this->data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function Add(Request $request)
    {
        // return $request->all();
        $ada = EstimasiPelayanan::where('kodepoli', $request->poli)->first();
        if($ada){
            return redirect()->route('EstimasiPelayanan')->with('title', 'Maaf !!')->with('message', 'Estimasi Pelayanan Sudah Ada')->with('type', 'error');
        } else {
            $cekPoli = DB::table('tm_poli')->where('kode_poli', $request->poli)->first();
            $layanan = new EstimasiPelayanan;
            $layanan->tampil = 1;
            $layanan->jamlayanan = $request->jam_layanan;
            $layanan->estimasi = $request->estimasi;
            $layanan->kodepoli = $request->poli;
            $layanan->poli = $cekPoli->NamaPoli;
            $layanan->save();

            if ($layanan) {
                return Redirect::route('EstimasiPelayanan')->with('title', 'Berhasil !!')->with('message', 'Estimasi Pelayanan Berhasil Ditambahkan')->with('type', 'success');
            }else{
                return redirect()->route('EstimasiPelayanan')->with('title', 'Maaf !!')->with('message', 'Estimasi Pelayanan Gagal Ditambahkan')->with('type', 'error');
            }
        }
    }

    public function tampil(Request $request)
    {
        $cek = EstimasiPelayanan::where("kodepoli", $request->id)->first();
        if($cek){
            $layanan = EstimasiPelayanan::find($request->id);
            if($cek->tampil == 0){
                $layanan->tampil = 1;
            } else {
                $layanan->tampil = 0;
            }
            $layanan->save();
            if($layanan){
                return ['status' => 'success', 'message' => 'Berhasil Merubah Status', 'tampil' => $layanan->tampil];
            }else{
                return ['status'=>'error', 'message'=>'Gagal Merubah Status'];
            }
        }
    }

    public function formUpdate(Request $request)
    {
        $this->data['tm_poli'] = DB::table('tm_poli')->get();
        $this->data['layanan'] = EstimasiPelayanan::where("kodepoli", $request->id)->first();
        $content = view('Admin.EstimasiPelayanan.formUpdate')->with('data', $this->data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function Updates(Request $request)
    {
        $layanan = EstimasiPelayanan::find($request->poli);
        $layanan->jamlayanan = $request->jam_layanan;
        $layanan->estimasi = $request->estimasi;
        $layanan->save();

        if ($layanan) {
            return Redirect::route('EstimasiPelayanan')->with('title', 'Berhasil !!')->with('message', 'Estimasi Pelayanan Berhasil Diperbaharui')->with('type', 'success');
        }else{
            return redirect()->route('EstimasiPelayanan')->with('title', 'Maaf !!')->with('message', 'Estimasi Pelayanan Gagal Diperbaharui')->with('type', 'error');
        }
    }

    public function delete(Request $request)
    {
        $layanan = EstimasiPelayanan::where("kodepoli", $request->id);
        if(!empty($layanan)){
            $layanan->delete();
            return ['status' => 'success', 'message' => 'Data berhasil dihapus'];
        }else{
            return ['status'=>'error', 'message'=>'Invalid user.'];
        }
    }
}
