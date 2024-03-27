<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\KotakSaran;
use File, Auth, Redirect, Validator;

class KotakSaranController extends Controller
{
    public function main(Request $request)
    {
        $this->data['classtutup'] = '';
        return view('Admin.KotakSaran.main')->with('data', $this->data);
    }

    public function datagrid(Request $request)
    {
        $data = KotakSaran::getJson($request);
        return response()->json($data);
    }

    public function detail(Request $request)
    {
        $data['saran'] = KotakSaran::find($request->id);
        $content = view('Admin.KotakSaran.detail', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function delete(Request $request)
    {
        $saran = KotakSaran::find($request->id);
        if(!empty($saran)){
            $saran->delete();
            return ['status' => 'success', 'message' => 'Data berhasil dihapus'];
        }else{
            return ['status'=>'error', 'message'=>'Invalid user.'];
        }
    }
}