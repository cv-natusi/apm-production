<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\AntPasienBaru;
use App\Http\Models\Antrian;
use App\Http\Models\Desa;
use App\Http\Models\Kabupaten;
use App\Http\Models\Kecamatan;
use App\Http\Models\Provinsi;
use App\Traits\ProfilPasienTraits;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

class ProfilPasienController extends Controller
{
	use ProfilPasienTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
		$provinsi = Provinsi::all();

        return view('Admin.profilpasien.main', compact('provinsi'));
    }

    public function getProfilPasien(Request $request){
		$nama = isset($request->filterNama) ? $request->filterNama : null;
		
		if ($request->ajax()) {
			$today = date('Y-m-d');
			
			if($nama == null){
				$data = DB::connection('dbrsud')->table('tm_customer')
					->orderBy('KodeCust', 'DESC')
					->limit(500)
					->get();
			}else{
				$data = DB::connection('dbrsud')->table('tm_customer')
					->where('NamaCust', 'like' , '%' . $nama . '%')
					->orderBy('KodeCust', 'DESC')
					->limit(500)
					->get();
			}

			//modifiData
			foreach ($data as $k => $vl) {
				$antrianPasienBaru = AntPasienBaru::where('cust_id', $vl->cust_id)->first();
				if(!empty($antrianPasienBaru)){
					$namaProv = !empty(Provinsi::where('id', $antrianPasienBaru->provinsi_id)->first()) ? Provinsi::where('id', $antrianPasienBaru->provinsi_id)->first()->name : "";
				}else{
					$namaProv = "";
				}
				$namaKab = !empty(Kabupaten::where('id', $vl->Kota)->first()) ? Kabupaten::where('id', $vl->Kota)->first()->name : "";
				$namaKec = !empty(Kecamatan::where('id', $vl->kecamatan)->first()) ? Kecamatan::where('id', $vl->kecamatan)->first()->name : "";
				$namaKel = !empty(Desa::where('id', $vl->kelurahan)->first()) ? Desa::where('id', $vl->kelurahan)->first()->name : "";

				$vl->namaProv = $namaProv;
				$vl->namaKab = $namaKab;
				$vl->namaKec = $namaKec;
				$vl->namaKel = $namaKel;
			}
            return Datatables::of(collect($data))
			->addIndexColumn()
			->addColumn('profil',function($row){
				return $this->templateProfil($row);
			})
			->addColumn('action',function($row){
				return $this->templateAction($row);
			})
			->make(true);
		}
		return view('Admin.profilpasien.main');
	}

    function templateAction($data){
		$btn = "";
		$btn .= "<div class='text-center'>";
		// $btn .= " <button class='btn btn-sm btn-danger btnPanggil' title='Panggil' onclick='panggil(id)'><i class='fa fa-bullhorn' aria-hidden='true'></i></button> &nbsp";
		$btn .= "<button class='btn btn-sm btn-primary' title='Detail' onclick='detail(".$data->cust_id.")'><i class='fa fa-eye' aria-hidden='true'></i></button> &nbsp";
		$btn .= "<button class='btn btn-sm btn-success' title='Edit' onclick='edit(".$data->cust_id.")'><i class='fa fa-pencil' aria-hidden='true'></i></button> &nbsp";
		$btn .= "</div>";

		return $btn;
	}

    function templateProfil($data){
		$btn = "";
        $btn .= "<div class='col-md-12'>";
        $btn .= "<div class='row'>";
        $btn .= "<div class='col-md-1'>";
		if( !empty($data->fotoCust) ){
			$btn .= "<img src='" . url('aset/images/fotopasien/'. $data->fotoCust) . "' alt='".$data->fotoCust."' style='width:25px; height=25px; border-radius:50%'>";
		}else{
			$btn .= "<img src=". url('aset/images/fotopasien/default.jpg') ." alt='imgpicture' style='width:25px; height=25px; border-radius:50%'>";
		}
		$btn .= "</div>";
		$btn .= "<div class='col-md-8'>";
        $btn .= "<p>".!empty($data->NamaCust) ? $data->NamaCust : "" ."</p>";
		$btn .= "</div>";
		$btn .= "</div>";
		$btn .= "</div>";

		return $btn;
	}

	public function simpanProfil(Request $request){
		try {
			$nameFoto = null;
			if($request->hasFile('foto_pasien')){
				$foto = $request->foto_pasien;
				$nameFoto = $request->nama_pasien . date('YmdHis') . "." .  $foto->getClientOriginalExtension();
				$foto->move(public_path('aset/images/fotopasien'), $nameFoto);
			}else{
				$nameFoto = isset($request->foto_pasien) ? $request->foto_pasien : null;
			}
			DB::beginTransaction();
			$dataPostTm = $this->generateTmCustomer($request->all(),$nameFoto);
			if(!isset($request->cust_id)){
				//jika insert
				$simpanTm = DB::connection('dbrsud')->table('tm_customer')->insertGetId($dataPostTm);
				$dataPostAPB = $this->generateAntrianPasienBaru($request->all(),$simpanTm);
				$simpanAPB = DB::connection('mysql')->table('antrian_pasien_baru')->insert([$dataPostAPB]);
				
				if($simpanAPB){
					DB::commit();
					return ['status' => 'success', 'code' => 200, 'message' => 'Berhasil Menyimpan Data Pasien'];
				}
			}else{
				//jika edit
				$cust_id = $request->cust_id;
				$simpanTm = DB::connection('dbrsud')->table('tm_customer')->where('cust_id', $cust_id)->update($dataPostTm);
				$dataPostAPB = $this->generateAntrianPasienBaru($request->all(),$cust_id);
				$simpanAPB = DB::connection('mysql')->table('antrian_pasien_baru')->where('cust_id', $cust_id)->update($dataPostAPB);
				
				if($simpanAPB || $simpanTm){
					DB::commit();
					return ['status' => 'success', 'code' => 200, 'message' => 'Berhasil Memperbarui Data Pasien'];
				}else{
					return ['status' => 'info', 'code' => 200, 'message' => 'Tidak Ada Perubahan Pada Data Pasien'];
				}
			}
		} catch (\Exception $th) {
			DB::rollback();
			return ['status' => 'error', 'code' => 500, 'message' => 'Gagal Menyimpan Data Pasien', 'messageerr' => $th->getMessage() , 'detailerr' => $th];
		}
	}
	
	public function generateNoRm(){
		// Generate NO.RM
		$getKode = DB::connection('dbrsud')->table('tm_customer')->max('KodeCust');
		$num = (int)substr($getKode, 5);
		$nextKode = 'W'.date("ym").(string)($num+1);
		$no_rm = $nextKode;
		
		if(!empty($no_rm)){
			return ['status' => 'success', 'code' => 200, 'message' => 'Berhasil Generate Nomor RM', 'data' => $no_rm];
		}else{
			return ['status' => 'error', 'code' => 500, 'message' => 'Gagal Generate Nomor RM'];
		}
	}

	public function view(Request $request){
		$provinsi = Provinsi::all();
		$data = DB::connection('dbrsud')->table('tm_customer')
					->where('cust_id', $request->cust_id)
					->first();
		//modifiData
		$antrianPasienBaru = AntPasienBaru::where('cust_id', $data->cust_id)->first();
		$namaProv = isset($$antrianPasienBaru->provinsi_id) ? Provinsi::where('id', $antrianPasienBaru->provinsi_id)->first()->name : "";
		$namaKab = !empty(Kabupaten::where('id', $data->Kota)->first()) ? Kabupaten::where('id', $data->Kota)->first()->name : "";
		$namaKec = !empty(Kecamatan::where('id', $data->kecamatan)->first()) ? Kecamatan::where('id', $data->kecamatan)->first()->name : "";
		$namaKel = !empty(Desa::where('id', $data->kelurahan)->first()) ? Desa::where('id', $data->kelurahan)->first()->name : "";

		$data->namaProv = $namaProv;
		$data->namaKab = $namaKab;
		$data->namaKec = $namaKec;
		$data->namaKel = $namaKel;
		$data->pend_terakhir = isset($antrianPasienBaru->pend_terakhir) ? $antrianPasienBaru->pend_terakhir : '';
		$data->view = $request->view;

		$content = view('Admin.profilpasien.formEdit', compact('data','provinsi'))->render();

		return ['status' => 'success', 'content' => $content, 'data' => $data];
	}
}