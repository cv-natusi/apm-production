<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\InformasiPenyakit;
use File, Auth, Redirect, Validator;

class InfoPenyakitController extends Controller
{
    public function main(Request $request)
    {
        $this->data['classtutup'] = '';
        return view('Admin.InformasiPenyakit.main')->with('data', $this->data);
    }

    public function datagrid(Request $request)
    {
        $data = InformasiPenyakit::getJson($request);
        return response()->json($data);
    }

    public function formAdd(Request $request)
    {
        $content = view('Admin.InformasiPenyakit.formAdd')->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function Add(Request $request)
    {
        $info = new InformasiPenyakit;
        $info->nama_penyakit = $request->nama_penyakit;
        $info->keterangan = $request->keterangan;
        $info->save();

        if ($info) {
            return Redirect::route('InfoPenyakit')->with('title', 'Berhasil !!')->with('message', 'Informasi Penyakit Berhasil Ditambahkan')->with('type', 'success');
        }else{
            return redirect()->route('InfoPenyakit')->with('title', 'Maaf !!')->with('message', 'Informasi Penyakit Gagal Ditambahkan')->with('type', 'error');
        }
    }

    public function detail(Request $request)
    {
        $data['info'] = InformasiPenyakit::find($request->id);
        $content = view('Admin.InformasiPenyakit.detail', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function formUpdate(Request $request)
    {
        $data['info'] = InformasiPenyakit::find($request->id);
        $content = view('Admin.InformasiPenyakit.formUpdate',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function Updates(Request $request)
    {
        $info = InformasiPenyakit::find($request->id_informasi_penyakit);
        $info->nama_penyakit = $request->nama_penyakit;
        $info->keterangan = $request->keterangan;
        $info->save();

        if ($info) {
            return Redirect::route('InfoPenyakit')->with('title', 'Berhasil !!')->with('message', 'Informasi Penyakit Berhasil Diperbaharui')->with('type', 'success');
        }else{
            return redirect()->route('InfoPenyakit')->with('title', 'Maaf !!')->with('message', 'Informasi Penyakit Gagal Diperbaharui')->with('type', 'error');
        }
    }

    public function delete(Request $request)
    {
        $info = InformasiPenyakit::find($request->id);
        if(!empty($info)){
            $info->delete();
            return ['status' => 'success', 'message' => 'Data berhasil dihapus'];
        }else{
            return ['status'=>'error', 'message'=>'Invalid user.'];
        }
    }
}
