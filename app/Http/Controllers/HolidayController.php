<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Holidays;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Helpers\apm as Help;
use File, Auth, Redirect, Validator,DB;

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
    
    public function datagridKuotaPoli(Request $request)
    {
        try {
            $data = Holidays::getJsonKuotaPoli($request);
            return response()->json($data);
        } catch (\Throwable $e) {
            $log = ['ERROR DATAGRID ('.$e->getFile().')',false,$e->getMessage(),$e->getLine()];
            Help::logging($log);
            return false;
        }
    }

    public function datagridLiburPoli(Request $request)
    {
        $data = Holidays::getJsonLiburPoli($request);
        return response()->json($data);
    }

    public function formAdd(Request $request)
    {
        $content = view('Admin.Holidays.formAdd')->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function formAddKuotaPoli(Request $request)
    {
        $data['poli'] = DB::connection('dbrsud')->table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->get();
        $content = view('Admin.Holidays.formAddKuotaPoli', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function formAddLiburPoli(Request $request)
    {
        $data['poli'] = DB::connection('dbrsud')->table('tm_poli as p')->join('mapping_poli_bridging as bp', 'bp.kdpoli_rs', '=', 'p.KodePoli')->get();
        $content = view('Admin.Holidays.formAddLiburPoli', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function Add(Request $request)
    {
        // return $request->all();
        // $cekHoliday = Holidays::where('tanggal',date('Y-m-d',strtotime($request->tanggal_libur)))->first();
        // if (!empty($cekHoliday)) {
            $holiday = new Holidays;
            $holiday->tanggal = date('Y-m-d',strtotime($request->tanggal_libur));
            $holiday->keterangan = strip_tags($request->keterangan);
            $holiday->kategori = "Libur Nasional";
            $holiday->save();

            if ($holiday) {
                return Redirect::route('holiday')->with('title', 'Berhasil !!')->with('message', 'Tanggal Libur Berhasil Ditambahkan')->with('type', 'success');
            }else{
                return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Gagal Ditambahkan')->with('type', 'error');
            }
        // }else{
        //     return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Sudah Di Pilih Sebagai Tanggal Libur')->with('type', 'error');
        // }
    }

    public function AddKuotaPoli(Request $request)
    {
        // return $request->all();
        // $cekHoliday = Holidays::where('tanggal',date('Y-m-d',strtotime($request->tanggal_libur)))->first();
        // if (!empty($cekHoliday)) {
            $holiday = new Holidays;
            $holiday->tanggal = date('Y-m-d',strtotime($request->tanggal_libur));
            $holiday->poli_id = $request->pilih_poli;
            $holiday->kuota_wa = $request->kuota_wa;
            $holiday->kuota_kiosk = $request->kuota_kiosk;
            $holiday->keterangan = strip_tags($request->keterangan);
            $holiday->kategori = "Kuota Poli";
            $holiday->save();

            if ($holiday) {
                return Redirect::route('holiday')->with('title', 'Berhasil !!')->with('message', 'Tanggal Libur Berhasil Ditambahkan')->with('type', 'success')->with('kuota_poli', true);
            }else{
                return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Gagal Ditambahkan')->with('type', 'error');
            }
        // }else{
        //     return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Sudah Di Pilih Sebagai Tanggal Libur')->with('type', 'error');
        // }
    }

    public function AddLiburPoli(Request $request)
    {
        // $cekHoliday = Holidays::where('tanggal',date('Y-m-d',strtotime($request->tanggal_libur)))->first();
        // if (!empty($cekHoliday)) {
            $holiday = new Holidays;
            $holiday->tanggal = date('Y-m-d',strtotime($request->tanggal_libur));
            $holiday->poli_id = $request->pilih_poli;
            $holiday->keterangan = strip_tags($request->keterangan);
            $holiday->kategori = "Libur Poli";
            $holiday->save();

            if ($holiday) {
                return Redirect::route('holiday')->with('title', 'Berhasil !!')->with('message', 'Tanggal Libur Berhasil Ditambahkan')->with('type', 'success')->with('libur_poli', true);
            }else{
                return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Gagal Ditambahkan')->with('type', 'error');
            }
        // }else{
        //     return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Sudah Di Pilih Sebagai Tanggal Libur')->with('type', 'error');
        // }
    }

    public function formUpdate(Request $request)
    {
        $data['holiday'] = Holidays::find($request->id);
        $content = view('Admin.Holidays.formUpdate',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function formUpdateKuotaPoli(Request $request)
    {
        try {
            $data['poli'] = DB::connection('dbrsud')->table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->get();
            $data['holiday'] = Holidays::find($request->id);
            $content = view('Admin.Holidays.formUpdateKuotaPoli',$data)->render();
            return ['status' => 'success', 'content' => $content];
        } catch (\Throwable $e) {
            $log = ['ERROR FORM KUOTA POLI ('.$e->getFile().')',false,$e->getMessage(),$e->getLine()];
            Help::logging($log);
            return false;
        }
        
    }

    public function formUpdateLiburPoli(Request $request)
    {
        $data['poli'] = DB::connection('dbrsud')->table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->get();
        $data['holiday'] = Holidays::find($request->id);
        $content = view('Admin.Holidays.formUpdateLiburPoli',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function Updates(Request $request)
    {
        // return $request->all();
        $cekHoliday = Holidays::where('id_holiday','=',$request->id_holiday)->first();
        if (!empty($cekHoliday)) {
            $holiday = Holidays::find($request->id_holiday);
            $holiday->tanggal = date('Y-m-d',strtotime($request->tanggal_libur));
            $holiday->keterangan = strip_tags($request->keterangan);
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

    public function UpdatesKuotaPoli(Request $request)
    {
        // return $request->all();
        $cekHoliday = Holidays::where('id_holiday','=',$request->id_holiday)->first();
        if (!empty($cekHoliday)) {
            $holiday = Holidays::find($request->id_holiday);
            $holiday->tanggal = date('Y-m-d',strtotime($request->tanggal_libur));
            $holiday->poli_id = $request->pilih_poli;
            $holiday->kuota_wa = $request->kuota_wa;
            $holiday->kuota_kiosk = $request->kuota_kiosk;
            $holiday->keterangan = strip_tags($request->keterangan);
            $holiday->save();

            if ($holiday) {
                return Redirect::route('holiday')->with('title', 'Berhasil !!')->with('message', 'Tanggal Libur Berhasil Diperbaharui')->with('type', 'success')->with('kuota_poli', true);
            }else{
                return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Gagal Diperbaharui')->with('type', 'error');
            }
        }else{
            return redirect()->route('holiday')->with('title', 'Maaf !!')->with('message', 'Tanggal Libur Sudah Di Pilih Sebagai Tanggal Libur')->with('type', 'error');
        }
    }

    public function UpdatesLiburPoli(Request $request)
    {
        // return $request->all();
        $cekHoliday = Holidays::where('id_holiday', '=', $request->id_holiday)->first();
        if (!empty($cekHoliday)) {
            $holiday = Holidays::find($request->id_holiday);
            $holiday->tanggal = date('Y-m-d',strtotime($request->tanggal_libur));
            $holiday->poli_id = $request->pilih_poli;
            $holiday->keterangan = strip_tags($request->keterangan);
            $holiday->save();

            if ($holiday) {
                return Redirect::route('holiday')->with('title', 'Berhasil !!')->with('message', 'Tanggal Libur Berhasil Diperbaharui')->with('type', 'success')->with('libur_poli', true);
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

    public function deleteKuotaPoli(Request $request)
    {
        $holiday = Holidays::find($request->id);
        if(!empty($holiday)){
            $holiday->delete();
            return ['status' => 'success', 'message' => 'Data berhasil dihapus'];
        }else{
            return ['status'=>'error', 'message'=>'Invalid user.'];
        }
    }

    public function deleteLiburPoli(Request $request)
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
