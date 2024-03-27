<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\PolaHidupSehat;
use File, Auth, Redirect, Validator;

class PolaHidupController extends Controller
{
    public function main(Request $request)
    {
        $this->data['classtutup'] = '';
        return view('Admin.PolaHidupSehat.main')->with('data', $this->data);
    }

    public function datagrid(Request $request)
    {
        $data = PolaHidupSehat::getJson($request);
        return response()->json($data);
    }

    public function formAdd(Request $request)
    {
        $content = view('Admin.PolaHidupSehat.formAdd')->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function Add(Request $request)
    {
        $pola = new PolaHidupSehat;
        $pola->judul = $request->judul;
        $pola->keterangan = $request->keterangan;
        $pola->save();

        if ($pola) {
            return Redirect::route('PolaHidup')->with('title', 'Berhasil !!')->with('message', 'Pola Hidup Sehat Berhasil Ditambahkan')->with('type', 'success');
        }else{
            return redirect()->route('PolaHidup')->with('title', 'Maaf !!')->with('message', 'Pola Hidup Sehat Gagal Ditambahkan')->with('type', 'error');
        }
    }

    public function detail(Request $request)
    {
        $data['pola'] = PolaHidupSehat::find($request->id);
        $content = view('Admin.PolaHidupSehat.detail', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function formUpdate(Request $request)
    {
        $data['pola'] = PolaHidupSehat::find($request->id);
        $content = view('Admin.PolaHidupSehat.formUpdate',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function Updates(Request $request)
    {
        $pola = PolaHidupSehat::find($request->id_pola_hidup_sehat);
        $pola->judul = $request->judul;
        $pola->keterangan = $request->keterangan;
        $pola->save();

        if ($pola) {
            return Redirect::route('PolaHidup')->with('title', 'Berhasil !!')->with('message', 'Pola Hidup Sehat Berhasil Diperbaharui')->with('type', 'success');
        }else{
            return redirect()->route('PolaHidup')->with('title', 'Maaf !!')->with('message', 'Pola Hidup Sehat Gagal Diperbaharui')->with('type', 'error');
        }
    }

    public function delete(Request $request)
    {
        $pola = PolaHidupSehat::find($request->id);
        if(!empty($pola)){
            $pola->delete();
            return ['status' => 'success', 'message' => 'Data berhasil dihapus'];
        }else{
            return ['status'=>'error', 'message'=>'Invalid user.'];
        }
    }
}
