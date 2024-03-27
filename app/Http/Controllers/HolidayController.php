<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Holidays;
use File, Auth, Redirect, Validator;

class HolidayController extends Controller
{
    public function main(Request $request)
    {
        $this->data['classtutup'] = '';
        return view('Admin.Holidays.main')->with('data', $this->data);
    }

    public function datagrid(Request $request)
    {
        $data = Holidays::getJson($request);
        return response()->json($data);
    }

    public function formAdd(Request $request)
    {
        $content = view('Admin.Holidays.formAdd')->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function Add(Request $request)
    {
        $cekHoliday = Holidays::where('tanggal',date('Y-m-d',strtotime($request->tanggal_libur)))->first();
        if (count($cekHoliday) == 0) {
            $holiday = new Holidays;
            $holiday->tanggal = date('Y-m-d',strtotime($request->tanggal_libur));
            $holiday->keterangan = $request->keterangan;
            $holiday->save();

            if ($holiday) {
                return Redirect::route('holiday')->with('title', 'Berhasil !!')->with('message', 'Tanggal Libur Berhasil Ditambahkan')->with('type', 'success');
            }else{
                return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Gagal Ditambahkan')->with('type', 'error');
            }
        }else{
            return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Sudah Di Pilih Sebagai Tanggal Libur')->with('type', 'error');
        }
    }

    public function formUpdate(Request $request)
    {
        $data['holiday'] = Holidays::find($request->id);
        $content = view('Admin.Holidays.formUpdate',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function Updates(Request $request)
    {
        $cekHoliday = Holidays::where('tanggal',date('Y-m-d',strtotime($request->tanggal_libur)))->where('id_holiday','!=',$request->id_holiday)->first();
        if (count($cekHoliday) == 0) {
            $holiday = Holidays::find($request->id_holiday);
            $holiday->tanggal = date('Y-m-d',strtotime($request->tanggal_libur));
            $holiday->keterangan = $request->keterangan;
            $holiday->save();

            if ($holiday) {
                return Redirect::route('holiday')->with('title', 'Berhasil !!')->with('message', 'Tanggal Libur Berhasil Diperbaharui')->with('type', 'success');
            }else{
                return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Gagal Diperbaharui')->with('type', 'error');
            }
        }else{
            return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Sudah Di Pilih Sebagai Tanggal Libur')->with('type', 'error');
        }
    }

    public function delete(Request $request)
    {
        $holiday = Holidays::find($request->id);
        if(!empty($holiday)){
            $holiday->delete();
            return ['status' => 'success', 'message' => 'Data berhasil dihapus'];
        }else{
            return ['status'=>'error', 'message'=>'Invalid user.'];
        }
    }
}
