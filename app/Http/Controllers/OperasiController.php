<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Rsu_operasi;
use App\Http\Models\Rsu_setupall;
use App\Http\Models\State;
use Validator;
use Auth;
use Redirect;
use Illuminate\Support\Str;
class OperasiController extends Controller
{
    public function main(Request $request)
    {
        $this->data['classtutup'] = ' sidebar-collapse';
        $list = Rsu_operasi::get();
        $data = [
			'data' => $list
		];
        return view('Admin.operasi.main', $data);
    }

    public function datagrid(Request $request)
    {
        $data = Rsu_operasi::getJson($request);
        return response()->json($data);
    }

    public function formAdd(Request $request)
    {
		$this->data['jenispasien'] = Rsu_setupall::where('groups','asuransi')->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])->get();
        $content = view('Admin.operasi.formAdd')->with('data', $this->data)->render();
        return ['status' => 'success', 'content' => $content];
    }
    public function formPasien(Request $request){
		$content = view('Admin.operasi.carifromrs')->render();
		return ['status' => 'success', 'content' => $content];
	}
    public function formPoli(Request $request){
		$content = view('Admin.operasi.cariformpolirs')->render();
		return ['status' => 'success', 'content' => $content];
	}
    public function formDPJP(Request $request){
		// return $request->all();
		$data['title'] = ($request->dpjp == 'layan') ? 'DPJP Layan' : 'Perujuk' ;
		$data['namaPoli'] = $request->poli;
		$content = view('Admin.operasi.caridpjp', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

    public function store(Request $request)
    {
         $latest = Rsu_operasi::orderBy('id_operasi','DESC')->first();
        $kodeBO = Str::substr($latest->id_operasi,2) + 1;
        if ($kodeBO < 10) {
            $kodeBO = '00000'.$kodeBO;
        }else if ($kodeBO < 100 ) {
            $kodeBO = '0000'.$kodeBO;
        }else if ($kodeBO < 1000 ) {
            $kodeBO = '000'.$kodeBO;
        }else if ($kodeBO < 10000 ) {
            $kodeBO = '00'.$kodeBO;
        }else if ($kodeBO < 100000 ) {
            $kodeBO = '0'.$kodeBO;
        }else{
            $kodeBO = ''.$kodeBO;
        }
        $data = New Rsu_operasi;
        $data->id_operasi = 'OP'.$kodeBO;
        $data->tanggal = $request->tanggal;
        $data->norm = $request->norm;
        $data->nama = $request->nama_pasien;
        $data->nobpjs = $request->no_bpjs;
        $data->penanggung = $request->jenisPeserta;
        $data->Kode_ruangan = $request->kdpoli;
        $data->Ruangan = $request->namapoli;
        $data->jenis_tindakan = $request->jenis_tindakan;
        $data->KodeDPJP = $request->dpjpLayan;
        $data->namaDPJP = $request->dpjp_layan;
        $data->status = 0;
        $data->save();
        if($data){
        return Redirect::route('operasi')->with('title', 'Berhasil !!')->with('message', 'Berhasil Ditambahkan')->with('type', 'success');
        }else{
        return redirect()->route('operasi')->with('title', 'Maaf !!')->with('message', 'Gagal Ditambahkan')->with('type', 'error');
        }
        // return response()->json($data);
    }

    public function ubahStatusOP(Request $request)
    {
        $data = Rsu_operasi::where('id_operasi',$request->id_operasi)->update(['status' => 1]);
        if ($data) {
            State::where('state_name', 'check_state_operasi')->update(['state' => 1]);
            return ['status' => 'success', 'message' => 'Berhasil di Ubah !!'];
        } else {
            return ['status'=>'error', 'message'=>'Gagal di Ubah !!'];
        }
    }
}
