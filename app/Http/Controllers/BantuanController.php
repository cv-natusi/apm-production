<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Bantuan;
use File, Auth, Redirect, Validator;

class BantuanController extends Controller
{
    public function main(Request $request)
    {
        $this->data['classtutup'] = '';
        return view('Admin.bantuan.main')->with('data', $this->data);
    }

    public function datagrid(Request $request)
    {
        $data = Bantuan::getJson($request);
        return response()->json($data);
    }

    public function formAdd(Request $request)
    {
        $content = view('Admin.bantuan.formAdd')->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function Add(Request $request)
    {
        $bantu = new Bantuan;
        $bantu->nama_bantuan = $request->nama_bantuan;
        $bantu->keterangan = $request->keterangan;
        $bantu->save();

        if ($bantu) {
            return Redirect::route('bantuan')->with('title', 'Berhasil !!')->with('message', 'Data Bantuan Berhasil Ditambahkan')->with('type', 'success');
        }else{
            return redirect()->route('bantuan')->with('title', 'Maaf !!')->with('message', 'Data Bantuan Gagal Ditambahkan')->with('type', 'error');
        }
    }

    public function detail(Request $request)
    {
        $data['bantu'] = Bantuan::find($request->id);
        $content = view('Admin.bantuan.detail', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function formUpdate(Request $request)
    {
        $data['bantu'] = Bantuan::find($request->id);
        $content = view('Admin.bantuan.formUpdate',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function Updates(Request $request)
    {
        $bantu = Bantuan::find($request->id_bantu);
        $bantu->nama_bantuan = $request->nama_bantuan;
        $bantu->keterangan = $request->keterangan;
        $bantu->save();

        if ($bantu) {
            return Redirect::route('bantuan')->with('title', 'Berhasil !!')->with('message', 'Data Bantuan Berhasil Diperbaharui')->with('type', 'success');
        }else{
            return redirect()->route('bantuan')->with('title', 'Maaf !!')->with('message', 'Data Bantuan Gagal Diperbaharui')->with('type', 'error');
        }
    }

    public function delete(Request $request)
    {
        $bantu = Bantuan::find($request->id);
        if(!empty($bantu)){
            $bantu->delete();
            return ['status' => 'success', 'message' => 'Data berhasil dihapus'];
        }else{
            return ['status'=>'error', 'message'=>'Invalid user.'];
        }
    }
}
