<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Libraries\Formatters;
use App\Http\Libraries\Requestor;
use App\Http\Controllers\Controller;
use App\Http\Models\Loket;
use App\Http\Models\Sep;
use App\Http\Models\Poli;
use App\Http\Models\Bridgingpoli;
use App\Http\Models\Customer;
use App\Http\Models\Setupall;
use App\Http\Models\Register;
use App\Http\Models\Tracer;
use App\Http\Models\Rawatjalan;
use App\Http\Models\Historypasien;
use App\Http\Models\diagnosaBpjs;
use App\Http\Models\Rsu_Bridgingpoli;
use App\Http\Models\Rsu_setupall;
use App\Http\Models\Rsu_Historypasien;
use App\Http\Models\Rsu_Register;
use App\Http\Models\Rsu_Tracer;
use App\Http\Models\rsu_poli;
use App\Http\Models\Rsu_Rawatjalan;
use App\Http\Models\rsu_customer;
use App\Http\Models\rsu_diagnosaBpjs;
use App\Http\Models\Rsu_cc;
use App\Http\Models\CC;
use App\Http\Models\RiwayatRegistrasi;
use App\Http\Models\RawatJalanTindakan;
use App\Http\Models\Rsu_RiwayatRegistrasi;
use App\Http\Models\Rsu_RawatJalanTindakan;
use App\Http\Models\rsu_dokter_bridging;
use App\Http\Models\VclaimPengajuanSep;
use Redirect, Validator, DB, Auth;

class BridgingController extends Controller
{
  public function main(Request $request){
    $this->data['classtutup'] = 'sidebar-collapse';
    //db lokal site
    /*$this->data['poli'] = Poli::all();
    $this->data['jenispasien'] = Setupall::where('groups','asuransi')->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])->get();*/

    //db rsu
    $this->data['poli'] = rsu_poli::all();
    $this->data['jenispasien'] = Rsu_setupall::where('groups','asuransi')->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])->get();

    return view('Admin.bridging.main')->with('data', $this->data);
  }

  public function trymain(Request $request){
    $this->data['classtutup'] = 'sidebar-collapse';
    //db lokal site
    /*$this->data['poli'] = Poli::all();
    $this->data['jenispasien'] = Setupall::where('groups','asuransi')->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])->get();*/

    //db rsu
    $this->data['poli'] = rsu_poli::all();
    $this->data['jenispasien'] = Rsu_setupall::where('groups','asuransi')->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])->get();

    return view('Admin.bridging.try_main')->with('data', $this->data);
  }

  public function carifromrs(Request $request){

    $content = view('Admin.bridging.carifromrs')->render();
    return ['status' => 'success', 'content' => $content];
  }

  public function caripasienrs(Request $request){

    $gopage = ($request->gopage) ? $request->gopage : 0;
    if ($request->gopage != 0) {
      $start = ($request->gopage - 1) * 15;
    }else{
      $start = ($request->start) ? $request->start : 0;
    }
    $end = ($request->end) ? $request->end : 15;
    // db lokal site
    /*$data = Customer::where($request->kat,'like','%'.$request->key.'%')->where('Alamat','like','%'.$request->alamat.'%')->offset($start)->limit($end)->get();
    $sum = Customer::where($request->kat,'like','%'.$request->key.'%')->where('Alamat','like','%'.$request->alamat.'%')->count();*/

    // db rsu
    $data = rsu_customer::where($request->kat,'like','%'.$request->key.'%')->where('Alamat','like','%'.$request->alamat.'%')->offset($start)->limit($end)->get();
    $sum = rsu_customer::where($request->kat,'like','%'.$request->key.'%')->where('Alamat','like','%'.$request->alamat.'%')->count();

    $return = ['status'=>'success','code'=>200,'data'=>$data,'sum'=>$sum,'gopage'=>$gopage];
    return response()->json($return);
  }

  public function getpasienrs(Request $request){
    // return $request->all();
    // $data = Customer::find($request->key); // db lokal site
    $data = rsu_customer::find($request->key); // db rsu

    $return = ['status'=>'success','code'=>200,'data'=>$data];
    return response()->json($return);
  }

  public function cariformpolirs(Request $request){
    $content = view('Admin.bridging.cariformpolirs')->render();
    return ['status' => 'success', 'content' => $content];
  }

  public function caripolirs(Request $request)
  {
    $polibridging = Bridgingpoli::select('kdpoli_rs')->get();
    $gopage = ($request->gopage) ? $request->gopage : 0;
    if ($request->gopage != 0) {
      $start = ($request->gopage - 1) * 15;
    }else{
      $start = ($request->start) ? $request->start : 0;
    }
    $end = ($request->end) ? $request->end : 15;

    //modal data dummy
    /*$data = DB::table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->where($request->kat,'like','%'.$request->key.'%')->offset($start)->limit($end)->get();
    $sum = DB::table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->where($request->kat,'like','%'.$request->key.'%')->count();*/

    // modal data rsu
    $data = DB::connection('dbrsud')->table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->where($request->kat,'like','%'.$request->key.'%')->offset($start)->limit($end)->get();
    $sum = DB::connection('dbrsud')->table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->where($request->kat,'like','%'.$request->key.'%')->count();

    $return = ['status'=>'success','code'=>200,'data'=>$data,'sum'=>$sum,'gopage'=>$gopage];
    return response()->json($return);
  }

  public function getpolinama(Request $request)
  {
    // modal data rsu
    // $data = DB::table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->where('NamaPoli','=',$request->key)->first(); // lokal
    $data = DB::connection('dbrsud')->table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->where('NamaPoli','=',$request->key)->first(); // rsu

    $return = ['status'=>'success','code'=>200,'data'=>$data];
    return response()->json($return);
  }

  public function cariformrmrs(Request $request){

    $content = view('Admin.bridging.carifromrmrs')->render();
    return ['status' => 'success', 'content' => $content];
  }

  public function carifromdiagnosars(Request $request)
  {
    $content = view('Admin.bridging.carifromdiagnosars')->render();
    return ['status' => 'success', 'content' => $content];
  }

  public function cariDiagnosars(Request $request)
  {
    $gopage = ($request->gopage) ? $request->gopage : 0;
    if ($request->gopage != 0) {
      $start = ($request->gopage - 1) * 15;
    }else{
      $start = ($request->start) ? $request->start : 0;
    }
    $end = ($request->end) ? $request->end : 15;
    // db lokal site
    /*$data = diagnosaBpjs::where($request->kat,'like','%'.$request->key.'%')->offset($start)->limit($end)->get();
    $sum = diagnosaBpjs::where($request->kat,'like','%'.$request->key.'%')->count();*/

    // db rsu
    $data = rsu_diagnosaBpjs::where($request->kat,'like','%'.$request->key.'%')->offset($start)->limit($end)->get();
    $sum = rsu_diagnosaBpjs::where($request->kat,'like','%'.$request->key.'%')->count();

    $return = ['status'=>'success','code'=>200,'data'=>$data,'sum'=>$sum,'gopage'=>$gopage];
    return response()->json($return);
  }


  /*
  */
  public function insertsep(Request $request){
    // return $request->all();
    /*$tg = date('y');
    $tg = $tg.'2';
    $thn = date('Y'); $mo = date('m'); $da = date('d');
    // $urut = Rsu_Register::whereRaw("YEAR(Tgl_Register) = $thn AND LEFT(No_Register,2) = $tg ")->orderby('No_Register','DESC')->limit(100)->get(); //db rsu
    $urut = Rsu_Register::whereRaw("(YEAR(Tgl_Register) = $thn) AND LEFT(No_Register,3) = $tg ")->orderby('No_Register','DESC')->first(); //db rsu
    // $urut = Rsu_Register::orderby('No_Register','DESC')->limit(100)->get(); //db rsu
    return $urut;*/

    // return $request->all();
    $rules = [
      'nokartu'=>'required',
      'tgl_sep'=>'required',
      'rawat'=>'required',
      // 'kelasrawat'=>'required',
      'jenisPeserta'=>'required',
      'katarak'=>'required',
      'no_rm'=>'required',
      'tgl_rujukan'=>'required',
      // 'no_rujukan'=>'required',
      'ppk_rujukan' =>'required',
      'catatan'=>'required',
      'diagnosa'=>'required',
      'poli' => 'required',
      // 'cob'=>'required',
      'laka'=>'required',
      'jenisPeserta'=>'required',
    ];
    if ($request->jnsRujukan == 'nonIgd') {
      $rules['no_rujukan'] = 'required';
    }
    // if ($request->rawat == '2') {
    //   $rules['poli'] = 'required';
    // }
    $messages = [
      'required' => 'kolom harus di isi',
    ];

    $valid = Validator::make($request->all(), $rules,$messages);
    if($valid->fails()){
      return $valid->messages();
    }else{
      // return $response;
      //end create signature

      // $curl = curl_init();
      $namauser = Auth::user()->name_user;
      $jaminan = '';
      for($i=0; $i<count($request->penjamin); $i++) {
        if($i == 0 ){
          $jaminan .= ''.$request->penjamin[$i];
        }else{
          $jaminan .= ','.$request->penjamin[$i];
        }
      }

      if ($request->kdDpjp != '') {
        // $cekSebelum = RiwayatRegistrasi::join('tr_registrasi','tr_registrasi.No_Register','=','riwayat_registrasi.No_Register')
        //                             ->where('no_rujukan', $request->no_rujukan)
        //                             ->orderBy('id_riwayat_regis','DESC')
        //                             ->first();
        // $cekSebelum = Rsu_RiwayatRegistrasi::where('no_rujukan', $request->no_rujukan)
        //                             ->orderBy('id_riwayat_regis','DESC')
        //                             ->first();
        // if (!empty($cekSebelum)) {
        //     $panjanNoAwal = strlen($cekSebelum->No_Register) - 6;
        //     $panjanNo = strlen($cekSebelum->No_Register);
        //     $noSurat = substr($cekSebelum->No_Register, $panjanNoAwal,$panjanNo);
        // }else{
        //     $regisSebelum = Rsu_Register::where('No_RM', $request->no_rm)->orderby('Tgl_Register','DESC')->first();
        //     $panjanNoAwal = strlen($regisSebelum->No_Register) - 6;
        //     $panjanNo = strlen($regisSebelum->No_Register);
        //     $noSurat = substr($regisSebelum->No_Register, $panjanNoAwal,$panjanNo);
        // }
        $noSurat = $request->no_surat;
      }else{
        // $noSurat = $request->no_surat;
        $noSurat = '';
      }

      date_default_timezone_set('Asia/Jakarta');
      $data =array(
        "request"=>[
          "t_sep"=> [
            "noKartu"=> $request->nokartu,   //
            "tglSep"=> $request->tgl_sep,  //
            "ppkPelayanan"=> "1320R001",  // kode fasiltas kesehatan pemberi pelayanan
            "jnsPelayanan"=> $request->rawat, // inputan manual
            "klsRawat"=> [
                "klsRawatHak"=>$request->kelasrawat,
                "klsRawatNaik"=>"",
                "pembiayaan"=>"",
                "penanggungJawab"=>""
            ],  //
            "noMR"=> $request->no_rm, //ambil dari RS  //
            "rujukan"=> [
              "asalRujukan"=> $request->tingkatRujuk,
              "tglRujukan"=> date('Y-m-d', strtotime($request->tgl_rujukan)),  //
              "noRujukan"=> $request->no_rujukan,  //
              "ppkRujukan"=> $request->ppk_rujukan  //
            ],
            "catatan"=> ($request->catatan) ? $request->catatan:"",  //
            "diagAwal"=> $request->diagnosa, // inputan  //
            "poli"=> [
              "tujuan"=> ($request->rawat == '2') ? $request->poli : '',  //
              "eksekutif"=> "0"
            ],
            "cob"=> [
              "cob"=> $request->cob
            ],
            "katarak" => [
              "katarak" => "0",
            ],
            "jaminan"=> [ // default 0; if 1 pilihan
              // "lakaLantas"=> $request->laka,  //
              // "penjamin"=> $jaminan,   //
              // "lokasiLaka"=> ($request->lokasi) ? $request->lokasi : '-',  //belum
              "lakaLantas" => $request->laka,
              // "penjamin" => '',
              "penjamin" => [
                'tglKejadian' => '2018-08-06',
                'keterangan' => 'kll',
                'suplesi' => [
                  'suplesi' => '0',
                  'noSepSuplesi' => '0301R0010718V000001',
                  'lokasiLaka' => [
                    'kdPropinsi' => '03',
                    'kdKabupaten' => '0050',
                    'kdKecamatan' => '0574'
                  ]
                ]
              ]
            ],
            "tujuanKunj"=>$request->tujuan_kunjugan,
            "flagProcedure"=> ($request->tujuan_kunjugan == 0) ? '' : $request->prosedur_bpjs,
            "kdPenunjang"=> ($request->tujuan_kunjugan == 0) ? '' : $request->penunjang_bpjs,
            "assesmentPel"=> ($request->tujuan_kunjugan == 0) ? '' : $request->assesment_bpjs,
            "skdp" => [
              "noSurat" => $noSurat,
              "kodeDPJP" => $request->kdDpjp,
            ],
            "dpjpLayan"=> ($request->rawat == '2') ? $request->id_dokterDpjpLayan : '', //
            "noTelp"=> ($request->notelp) ? $request->notelp : '000000000000',
            "user"=> $namauser
          ]
        ]
      );
      // return $data;
      // $url="http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/SEP/insert"; //url web service bpjs
      $url="https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/SEP/2.0/insert"; //url web dev service bpjs
      // $url="https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/SEP/2.0/insert"; //url web rilis baru service bpjs
      //create signature
      $consID     = "21095"; //customer ID RS
      $secretKey  = "rsud6778ws122mjkrt"; //secretKey RS
      $datas = json_encode($data);
      $sendBpjs = $datas;
      $method = 'POST';
      date_default_timezone_set('UTC');
      $stamp      = strval(time()-strtotime('1970-01-01 00:00:00'));
      $data       = $consID.'&'.$stamp;

      $signature = hash_hmac('sha256', $data, $secretKey, true);
      $encodedSignature = base64_encode($signature);
      $key = $consID.$secretKey.$stamp;
      // return $key;
      $ch = curl_init();

      // return ['encodedSignature'=> $encodedSignature,'stamp' => $stamp];
      curl_setopt_array($ch, array(
          // CURLOPT_PORT => "8080",
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $method,
          CURLOPT_POSTFIELDS => $datas,
          CURLOPT_HTTPHEADER => array(
              "cache-control: no-cache",
              "x-cons-id: 21095",
              "x-signature: ".$encodedSignature,
              "x-timestamp: ".$stamp."",
              "user_key: 21f330a3e8e9f281d845f6b545b23653",
          ),
      ));
      $response = curl_exec($ch);
      $err = curl_error($ch);
      curl_close($ch);
      if ($err) {
        return "cURL Error #:" . $err;
      } else {
        $respon = json_decode($response,true);
        // return $respon;
        if ($respon['metaData']['code'] != 200 && !preg_match('/belum dipulangkan di RSUD DR. W. SUDIROHUSODO/i', $respon['metaData']['message'])) {
        // if ($respon['metaData']['code'] != 200 && !preg_match('/telah mendapat Pelayanan R.Inap/i', $respon['metaData']['message'])) {
          return ['status'=>'error', 'code'=> $respon['metaData']['code'], 'messages'=>$respon['metaData']['message']];
        }
        $string = json_encode($respon['response']);
        // FUNGSI DECRYPT
        $encrypt_method = 'AES-256-CBC';
        // hash
        $key_hash = hex2bin(hash('sha256', $key));
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
        $value = \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
        $respon2 = json_decode($value,true);
        if ($respon['response'] == null) {
          $cek = explode('No.SEP ', $respon['metaData']['message']);
          if (count($cek) > 1) {
            $nosep = substr($cek[1], 0,19);

            // $pulang = preg_match('/belum dipulangkan di RSUD DR. W. SUDIROHUSODO/i', $respon['metaData']['message']);
            $pulang = preg_match('/telah mendapat Pelayanan R.Inap/i', $respon['metaData']['message']);
            $rsu = preg_match('/1320R001/i', $respon['metaData']['message']);
            if($pulang && $rsu){
              $ek = 'update';
            }else{
              $ek = "";
            }
            return ['status'=>'error', 'code'=> $respon['metaData']['code'], 'messages'=>$respon['metaData']['message'], 'update' => $ek, 'nobpjs'=>$nosep, 'tingkat'=>'1', 'sendBpjs' => $sendBpjs];
          }else{
            $tingkatRujuk = preg_match('/Asal rujukan Harus Diisi 2/i', $respon['metaData']['message']);
            if ($tingkatRujuk) {
              return ['status'=>'error', 'code'=> $respon['metaData']['code'], 'messages'=>$respon['metaData']['message'], 'update' => 'tingkat', 'nobpjs'=>'', 'tingkat'=>'2', 'sendBpjs' => $sendBpjs];
            }else{
              return ['status'=>'error', 'code'=> $respon['metaData']['code'], 'messages'=>$respon['metaData']['message'], 'update' => '', 'nobpjs'=>'', 'tingkat'=>'1', 'sendBpjs' => $sendBpjs];
            }
          }
        }else{
          // if ($request->rawat == '2') {
            // $poli = Bridgingpoli::where('kdpoli',$request->poli)->select('kdpoli_rs')->first(); //get poli rs -> DB Lokal
            $poli = Rsu_Bridgingpoli::where('kdpoli', $request->poli)->select('kdpoli_rs')->first(); //get poli rs -> DB Rsu
            // return $poli;
            // $asu = Setupall::where('subgroups',$request->jenisPeserta)->select('nilaichar')->first(); //get nama asuransi RS -> DB Lokal
            $asu = Rsu_setupall::where('subgroups',$request->jenisPeserta)->select('nilaichar')->first(); //get nama asuransi RS -> DB RSU
            // $namapoli = Poli::find($poli->kdpoli_rs); // DB Lokal
            // $namapoli = rsu_poli::find($poli->kdpoli_rs); // DB Rsu
          // }

          // Insert tabel Register
          $reg = new Rsu_Register; // db rsu
          // $reg = new Register; //db lokal site
          $reg->TransReg = 'RE';
          $tg = date('y');
          $tg =$tg.'2';
          $thn = date('Y'); $mo = date('m'); $da = date('d');
          //$urut = Rsu_Register::whereRaw("YEAR(Tgl_Register) = $thn AND month(tgl_register)= $mo and day(tgl_register)= $da AND LEFT(No_Register,2) = $tg ")->orderby('No_Register','DESC')->first(); //db rsu
          // $urut = Rsu_Register::whereYEAR('Tgl_Register','=',date('Y'))->select('No_Register','Tgl_Register')->orderby('No_Register','DESC')->first(); //db rsu
          // $urut = Register::whereYEAR('Tgl_Register','=',date('Y'))->select('No_Register','Tgl_Register')->orderby('No_Register','DESC')->first(); //db lokal site

          $urut = Rsu_Register::whereRaw("(YEAR(Tgl_Register) = $thn) AND LEFT(No_Register,3) = $tg ")->orderby('No_Register','DESC')->first(); //db rsu use
          // $urut = Register::whereRaw("(YEAR(Tgl_Register) = $thn) AND LEFT(No_Register,3) = $tg ")->orderby('No_Register','DESC')->first(); //db lokal new

          if($urut){
            $nourut = $urut->No_Register + 1;
          }else{
            $nourut = date('y').'20000001';
          }

          $reg->No_Register = $nourut;
          $reg->Tgl_Register = date('Y-m-d H:i:s');
          $reg->Jam_Register = date('H:i:s');
          $reg->No_RM =  $request->no_rm;
          $reg->Nama_Pasien = $request->nama;
          $reg->AlamatPasien = $request->alamat;
          $reg->Umur = $request->umur;
          $reg->Kode_Ass = $request->jenisPeserta;
          $reg->Kode_Poli1 = isset($poli->kdpoli_rs) ? $poli->kdpoli_rs : '';
          $reg->JenisKel = $request->sex;
          $reg->Rujukan = $request->no_rujukan;
          $reg->NoSEP = $respon2['sep']['noSep'];
          $reg->NoPeserta = $request->nokartu;
          $reg->Biaya_Registrasi = 5000;
          $reg->Status = 'Belum Dibayar';
          $reg->NamaAsuransi = isset($asu->nilaichar) ? $asu->nilaichar : '';
          $reg->Japel = 3000;
          $reg->JRS = 2000;
          $reg->TipeReg = 'REG';
          $reg->SudahCetak = 'N';
          $reg->BayarPendaftaran = 'N';
          $reg->Tgl_Lahir = $request->tgl_lahir;
          $reg->isKaryawan = ($request->karyawan) ? $request->karyawan : 'N';
          $reg->isProcessed = 'N';
          $reg->isPasPulang = 'N';
          $reg->Jenazah     = 'N';
          $reg->save();

          if(!empty($request->apm)){
            // $updateCC = CC::where('norm',$request->no_rm) // db lokal
            $updateCC = Rsu_cc::where('norm',$request->no_rm) // db rsu
            ->where('tanggal',date('d-m-Y'))
            ->where('nourut', $request->noUrut)
            ->update(['status' => 'Sudah']);
          }

          $methodRujukan = 'GET';
          $paramsRujukan = '';
          $portRujukan = '80';
          if ($request->kdDpjp == '') {
            $urlRujukan = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/Rujukan/".$request->no_rujukan; //url web service bpjs develop
            // $urlRujukan = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/".$request->no_rujukan; //url web service bpjs rilis
            $resultRujukan = Requestor::set_new_curl_bridging($urlRujukan, $paramsRujukan, $methodRujukan, $consID, $secretKey, $portRujukan,'',''); // bridging data peserta bpjs
            if ($resultRujukan === false) {
              return ['status' => 'error', 'message' => 'Tidak Dapat Terhubung ke Server !!'];
            } else {
              $resultRujukans = [
                'metaData' => json_decode($resultRujukan['metaData']),
                'response' => json_decode($resultRujukan['response']),
              ];
              if ($resultRujukans['response'] == '') {
                $urlRujukan2 = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/Rujukan/RS/".$request->noRujuk; //url web service bpjs develop
                // $urlRujukan2 = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/RS/".$request->no_rujukan; //url web service bpjs rilis
                $resultRujukan2 = Requestor::set_new_curl_bridging($urlRujukan2, $paramsRujukan, $methodRujukan, $consID, $secretKey, $portRujukan,'',''); // bridging data peserta bpjs
                if ($resultRujukan2 === false) {
                  return ['status' => 'error', 'messages' => 'Tidak Dapat Terhubung ke Server !!'];
                }else{
                  $resultRujukans = [
                    'metaData' => json_decode($resultRujukan2['metaData']),
                    'response' => json_decode($resultRujukan2['response']),
                  ];
                  if ($resultRujukans['response'] != '') {
                    $prosHas = 1;
                  }else{
                    $prosHas = 0;
                  }
                }
              }else{
                $prosHas = 1;
              }
              if ($prosHas == 1) {
                // if ($resultRujukans != null) {
                $resKodePoli = $resultRujukans['response']->rujukan->poliRujukan->kode;
              }else{
                $messages = $resultRujukans['metaData']['message'];
                $return = ['status' => 'error', 'message' => $messages];
              }
            }
            $dokterBridg = rsu_dokter_bridging::where('polibpjs',$resKodePoli)->first();
            $urlDokter = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/referensi/dokter/pelayanan/".$request->rawat."/tglpelayanan/".date('Y-m-d')."/Spesialis/".$resKodePoli; // url develop
            // $urlDokter = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/referensi/dokter/pelayanan/".$request->rawat."/tglpelayanan/".date('Y-m-d')."/Spesialis/".$resKodePoli; // url rilis
            $kdPoliRw = $resKodePoli;
          }else{
            $kdDok = ($request->jnsRujukan == 'igd') ? '1' : $request->rawat;
            if ($request->jnsRujukan == 'igd') {
              $dokterBridg = rsu_dokter_bridging::where('polibpjs', $request->poli)->first();
            } else {
              $dokterBridg = rsu_dokter_bridging::where('polibpjs', $request->poli)->first();
            }
            $urlDokter = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/referensi/dokter/pelayanan/".$kdDok."/tglpelayanan/".date('Y-m-d')."/Spesialis/".$request->poli; // url develop
            // $urlDokter = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/referensi/dokter/pelayanan/".$request->rawat."/tglpelayanan/".date('Y-m-d')."/Spesialis/".$request->poli; // url rilis
            $kdPoliRw = $request->poli;
          }
          $resultDokter = Requestor::set_new_curl_bridging($urlDokter, $paramsRujukan, $methodRujukan, $consID, $secretKey, $portRujukan,'','');
          // $hslDokter = json_decode($resultDokter, true);
          $hslDokter = [
            'metaData' => json_decode($resultDokter['metaData']),
            'response' => json_decode($resultDokter['response']),
          ];
          // return $resultDokter;
          $kodeDPJPRiw = '';
          $namaDPJPRiw = '';
          // IF IGD
          if ($request->jnsRujukan == 'igd') {
            foreach ($hslDokter['response']->list as $key) {
              if (strtolower($key->kode) == $request->id_dokterDpjpLayan) {
                $kodeDPJPRiw = $key->kode;
                $namaDPJPRiw = $key->nama;
              }
            }
          } else {
            foreach ($hslDokter['response']->list as $key) {
              if (strtolower($key->nama) == strtolower($dokterBridg->dokter)) {
                $kodeDPJPRiw = $key->kode;
                $namaDPJPRiw = $key->nama;
              }
            }
          }

          // $addHistory = new RiwayatRegistrasi; // db lokal
          $addHistory = new Rsu_RiwayatRegistrasi; // db rsu
          $addHistory->No_Register = $nourut;
          $addHistory->no_surat = $noSurat;
          $addHistory->no_rm = $request->no_rm;
          $addHistory->no_rujukan = $request->no_rujukan;
          $addHistory->NoSEP = $respon2['sep']['noSep'];
          $addHistory->kode_dpjp = $kodeDPJPRiw;
          $addHistory->nama_dpjp = $namaDPJPRiw;
          $addHistory->poli_bpjs = $kdPoliRw;
          $addHistory->save();

          $regi = ($reg) ? 'reg berhasil':'reg gagal';
          if(!empty($request->apm)){
            // $updateCC = CC::where('norm',$request->no_rm) // db lokal
            $updateCC = Rsu_cc::where('norm',$request->no_rm) // db rsu
            ->where('tanggal',date('d-m-Y'))
            ->where('nourut', $request->noUrut)
            ->update(['status' => 'Sudah']);
          }

          // $noarsip = Register::whereBetween('Tgl_Register',[date('Y-m-d').' 00:00:00',date('Y-m-d').' 23.59.59'])->count('No_Register'); // db lokal
          $noarsip = Rsu_Register::whereBetween('Tgl_Register',[date('Y-m-d').' 00:00:00',date('Y-m-d').' 23.59.59'])->count('No_Register'); // db rsu
          $noarsip = ($noarsip == 0 ) ? 1 : $noarsip;
          return [
            'status'=>'success',
            'code'=>'200',
            'messages'=>"return",
            'nosep'=> $respon2['sep']['noSep'],
            'sep'=>$respon2,
            'reg'=>$regi,
            'noarsip'=>$noarsip,
            'noKontrol' => $nourut,
            'dtRegis' => $reg,
            'sendBpjs' => $sendBpjs
          ];
        }
      }
    }
  }

  public function cekcustomer(Request $request){
    return rsu_customer::find('S1212000253');
  }

  // web service
  public function cetaksepservice(Request $request)
  {
    $sep = $request->nosep ? $request->nosep : '0000000000000000000';
    $url = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/SEP/".$sep; //url web dev service bpjs
    // $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/SEP/".$sep; //url web rilis baru service bpjs
    // $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/SEP/".$sep; //url web service bpjs
    $consID     = "21095"; //customer ID RS
    $secretKey  = "rsud6778ws122mjkrt"; //secretKey RS
    $method = 'GET';
    $port = '80';
    $params = '';

    $datasep = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'','');
    // $result = json_decode($datasep,true);
    $result = [
      'metaData' => json_decode($datasep['metaData']),
      'response' => json_decode($datasep['response']),
    ];
    if ($result['metaData']->code == 200) {
      date_default_timezone_set('Asia/Jakarta');

      $data['sepValue'] = [
        'jam' => date('d-m-Y H:i:s'),
        'no_sep' => $result['response']->noSep,
        'tgl_sep' => $result['response']->tglSep,
        'no_kartu' => $result['response']->peserta->noKartu,
        'noMr' => $result['response']->peserta->noMr,
        'nama_kartu' => $result['response']->peserta->nama,
        'tgl_lahir' => $result['response']->peserta->tglLahir,
        'jenis_kelamin' => $result['response']->peserta->kelamin,
        'poli_tujuan' => $result['response']->poli,
        'diagnosa' => $result['response']->diagnosa,
        'jenis_rawat' => $result['response']->jnsPelayanan,
        'catatan' => $result['response']->catatan,
        'kls_rawat' => $result['response']->peserta->hakKelas,
        'noarsip' => $request->noarsip,
      ];

      $return = ['status'=>'success','code'=>200,'data'=>$data];
      return response()->json($return);
    }elseif ($result['metaData']->code == 201) {
      return $result;
    }else {
      return $result;
    }
  }

  //update tanggal pulang
  public function saveChange(Request $request){
    // return $request->all();
    // return strlen($request->nobpjspulang);
    $url = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/Sep/updtglplg"; //url web dev service bpjs
    // $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Sep/updtglplg"; //url web rilis baru service bpjs
    // $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/Sep/updtglplg"; //url web service bpjs
    $consID     = "21095"; //customer ID RS
    $secretKey  = "rsud6778ws122mjkrt"; //secretKey RS
    $method = 'PUT';
    $port = '80';

    $namauser = Auth::user()->name_user;
    $data =array(
      "t_sep"=> [
        "noSep"=> $request->nobpjspulang,   //
        "statusPulang"=> $request->statusPulang,
        "tglMeninggal"=> $request->tglMeninggal,
        "noSuratMeninggal"=> $request->noSuratMeninggal,
        "tglPulang"=> date('Y-m-d', strtotime($request->tglpulang)),
        "noLPManual"=> $request->noLPManual,
        // "ppkPelayanan"=>"1320R001",
        "user" => $namauser,
        ],
      );
    // return $data;
    $params = json_encode(array('request' => $data));

    $result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
    if ($result === false) {
      echo "Tidak dapat menyambung ke server";
    } else {
      $respon = [
        'metaData' => json_decode($result['metaData']),
        'response' => json_decode($result['response']),
      ];
      return $respon;
    }
  }

  // get history pasien
  public function gethistorypasien(Request $request){
    // $history = Historypasien::where('no_rm',$request->norm)->limit(10)->get(); // db lokal site
    // $history = Rsu_Historypasien::where('no_rm',$request->norm)->limit(10)->get(); //db rsu
    $history = Rsu_RiwayatRegistrasi::where('no_rm',$request->norm)->limit(10)->get(); //db rsu
    // $history = DB::connection('dbrsud')->SELECT('SELECT * FROM (SELECT "RJ", tr_tracer.No_Register, tr_tracer.Nama_Pasien, DATE(tr_tracer.Tgl_Register) AS TANGGAL, tr_tracer.NAMAPOLI, tr_tracer.NamaAsuransi, tr_registrasi.NoSEP FROM tr_tracer, tr_registrasi WHERE tr_tracer.No_Register = tr_registrasi.No_Register  AND tr_tracer.No_RM="'.$request->norm.'" UNION ALL
    // SELECT "RI", No_register, NamaPasien, DATE(Tgl_masuk) AS TANGGAL,NamaKamar, StatusBayar, "" as NoSEP  FROM tm_rawatinap  WHERE tm_rawatinap.NO_RM="'.$request->norm.'")  AS XX  ORDER BY TANGGAL DESC limit 10');
    // return $history;
    return  ['status'=>'success','code'=>'200','history'=>$history];
  }

  //cek peserta bpjs
  public function cekpeserta(Request $request){
    // return $request->all();
    $sep = $request->nobpjs ? $request->nobpjs : '0000000000000000000';
    if ($request->jnsCari == 'nik') {
      $url = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/Peserta/nik/".$request->nobpjs."/tglSEP/".date('Y-m-d'); //url web dev service bpjs
    } else {
      $url = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web dev service bpjs
      // $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web rilis baru service bpjs
      // $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/nokartu/".$request->nobpjs."/tglsep/".date('Y-m-d'); //url web service bpjs
    }

    $consID     = "21095"; //customer ID RS
    $secretKey  = "rsud6778ws122mjkrt"; //secretKey RS
    $method = 'GET';
    // $port = '8080';
    $port = '80';
    $params = '';

    $result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
    if ($result === false) {
      echo "Tidak dapat menyambung ke server";
    } else {
        $respon = [
          'metaData' => json_decode($result['metaData']),
          'response' => json_decode($result['response']),
        ];
      return $respon;
    }
  }

  //form update tanggal pulang
  public function formupdatetglpulang(Request $request){
    $data['nobpjs'] = $request->nobpjs;
    $data['messages'] = $request->pesan;
    $content = view('Admin.bridging.formupdatetglpulang', $data)->render();
    return ['status' => 'success', 'content' => $content];
  }

  /*public function updatetglpulang(Request $request){

  $sep = $request->nobpjs ? $request->nobpjs : '0000000000000000000';
  $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/peserta/Sep/updtglplg"; //url web service bpjs
  $consID     = "21095"; //customer ID RS
  $secretKey  = "rsud6778ws122mjkrt"; //secretKey RS
  $method = 'PUT';
  $port = '8080';
  $param = array(
  "t_sep"=> [
  "noSep"=> $request->nokartu,   //
  "tglPulang"=> $request->tgl_sep,  //
  "user"=> Auth::user()->name_user
  ]
  );
  $params = json_encode($param);
  $result = Requestor::set_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
  if ($result === false) {
  echo "Tidak dapat menyambung ke server";
} else {
  return json_decode($result, true);
}
}*/

// update no kartu bpjs di rm
public function changeNokartu(Request $request){
  // return $request->data['kdcust'];
  // $customer = Customer::where('KodeCust',$request->data['kdcust'])->update([$request->data['field'] => $request->data['value']]); // db db lokal
  $customer = rsu_Customer::where('KodeCust',$request->data['kdcust'])->update([$request->data['field'] => $request->data['value']]); // db rsu
  if($customer){
    return 'berhasil update';
  }else{
    return 'gagal update';
  }
}


public function getregister(Request $request){
  // return $request->all();
  // $data = Register::findOrfail($request->noregister); // db lokal
  $data = Rsu_Register::find($request->noregister); // db rsu
  if($data){
    // return $data;
    $url = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/SEP/".$data->NoSEP; //url web dev service bpjs
    // $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/SEP/".$data->NoSEP; //url web rilis baru service bpjs
    // $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/SEP/".$data->NoSEP; //url web service bpjs
    $consID     = "21095"; //customer ID RS
    $secretKey  = "rsud6778ws122mjkrt"; //secretKey RS
    $method = 'GET';
    $port = '80';
    $params = '';

    $datasep = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'','');
    $result = [
      'metaData' => json_decode($datasep['metaData']),
      'response' => json_decode($datasep['response']),
    ];
    // return $result;
    date_default_timezone_set('Asia/Jakarta');

    $dat['Kode_Ass'] = $data->Kode_Ass;
    $dat['NoSEP'] = $data->NoSEP;
    $dat['catatan'] = $result['response']->catatan;
    // $poli = DB::table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->where('kdpoli_rs',$data->Kode_Poli1)->first(); // db lokal
    $poli = DB::connection('dbrsud')->table('tm_poli as p')->join('mapping_poli_bridging as bp','bp.kdpoli_rs','=','p.KodePoli')->where('kdpoli_rs',$data->Kode_Poli1)->first(); // db rsu
    $dat['kdpoli'] = $poli->kdpoli;
    $dat['namapoli'] = $poli->NamaPoli;
    if($result['response']->diagnosa != ''){
      // $kd_diagnosa = diagnosaBpjs::where('diagnosa','like','%'.$result['diagnosa'].'%')->first(); // db lokal
      $kd_diagnosa = rsu_diagnosaBpjs::where('diagnosa','like','%'.$result['response']->diagnosa.'%')->first(); // db rsu
      $dat['kddiagnosa'] = $kd_diagnosa->KodeICD;
      $dat['diagnosa'] = $result['response']->diagnosa;
    }else{
      $dat['kddiagnosa'] = '';
      $dat['diagnosa'] = '';
    }

    $riwayat = Rsu_RiwayatRegistrasi::where('NoSEP', $data->NoSEP)->first();
    if (!empty($riwayat)) {
      $dat['noRujuk'] = $riwayat->no_rujukan;
    }else{
      $dat['noRujuk'] = '';
    }

    return ['status'=>'success','code'=>'200','data'=>$dat];
  }else{
    return ['status'=>'success','code'=>'404','data'=>$data];
  }
}

  public function cekrujukan(Request $request)
  {
    // return $request->all();
    date_default_timezone_set('Asia/Jakarta');
    // $url = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/Rujukan/Peserta/".$request->nobpjs; // url develop
    if (!empty($request->nobpjs)) {
      $url = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/Rujukan/Peserta/".$request->nobpjs; // url develop

      // $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/Peserta/".$request->nobpjs; // url rilis
    }else{
      $url = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/Rujukan/".$request->noRujuk; // url develop
      // $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/".$request->noRujuk; // url rilis
    }
    $consID     = "21095"; //customer ID RS
    $secretKey  = "rsud6778ws122mjkrt"; //secretKey RS
    $method = 'GET';
    $port = '80';
    $params = '';
    $result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'','');
    if ($result === false) {
      return ['status' => 'error', 'message' => 'Tidak Terhubung ke Server !!'];
    }else{
      // return $result;
      $results = [
        'metaData' => json_decode($result['metaData']),
        'response' => json_decode($result['response']),
      ];
      $data['tingkatRujuk'] = '1';
      if ($results['response'] == '') {
        $url2 = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/Rujukan/RS/Peserta/".$request->nobpjs; //url web service bpjs develop
        if (!empty($request->nobpjs)) {
          $url2 = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/Rujukan/RS/Peserta/".$request->nobpjs; //url web service bpjs develop
          // $url2 = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/RS/Peserta/".$request->nobpjs; //url web service bpjs rilis
        }else{
          $url2 = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/Rujukan/RS/".$request->noRujuk; //url web service bpjs develop
          // $url2 = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Rujukan/RS/".$request->noRujuk; // url rilis
        }
        $result2 = Requestor::set_new_curl_bridging($url2, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
        // $result2 = Requestor::set_curl_bridging_new($url2, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
        if ($result2 === false) {
          return ['status' => 'error', 'messages' => 'Tidak Dapat Terhubung ke Server !!'];
        }else{
          $results = [
            'metaData' => json_decode($result2['metaData']),
            'response' => json_decode($result2['response']),
          ];
          if ($results['response'] != null) {
            $prosHas = 1;
            $data['tingkatRujuk'] = '2';
          }else{
            $prosHas = 0;
          }
        }
      }else{
        $prosHas = 1;
      }

      $kdDok = ($request->kdpoli == 'IGD') ? '1' : $request->rawat;
      $urlDpjp = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/referensi/dokter/pelayanan/".$kdDok."/tglPelayanan/".date('Y-m-d')."/Spesialis/".$request->kdpoli; // url develop
      // $urlDokter = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/referensi/dokter/pelayanan/".$request->rawat."/tglpelayanan/".date('Y-m-d')."/Spesialis/".$cekSebelum->poli_bpjs; // url rilis
      $resultDpjp = Requestor::set_new_curl_bridging($urlDpjp, $params, $method, $consID, $secretKey, $port,'','');

      $listDpjp = [
        'metaData' => json_decode($resultDpjp['metaData']),
        'response' => json_decode($resultDpjp['response']),
      ];
      if ($prosHas == 1) {
        // return print_r($results['response']->rujukan);
        $data['rujukan'] = $results;
        if(!empty($request->rm)){
          $cekSebelum = Rsu_RiwayatRegistrasi::where('no_rujukan', $results['response']->rujukan->noKunjungan)
          ->orderBy('id_riwayat_regis','DESC')
          ->first();
          $kodeDPJP = '';
          $namaDPJP = '';
          $riwayat = '0';
          if (!empty($cekSebelum)) {
            $urlDokter = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/referensi/dokter/pelayanan/".$request->rawat."/tglPelayanan/".date('Y-m-d')."/Spesialis/".$cekSebelum->poli_bpjs; // url develop
            // $urlDokter = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/referensi/dokter/pelayanan/".$request->rawat."/tglpelayanan/".date('Y-m-d')."/Spesialis/".$cekSebelum->poli_bpjs; // url rilis
            $resultDokter = Requestor::set_new_curl_bridging($urlDokter, $params, $method, $consID, $secretKey, $port,'','');

            $hslDokter = [
              'metaData' => json_decode($resultDokter['metaData']),
              'response' => json_decode($resultDokter['response']),
            ];

            foreach ($hslDokter['response']->list as $k) {
              if (strtolower($k->nama) == strtolower($cekSebelum->nama_dpjp)) {
                $kodeDPJP = $k->kode;
                $namaDPJP = $k->nama;
              }
            }
            $riwayat = '1';

            $panjanNoAwal = strlen($cekSebelum->No_Register) - 6;
            $panjanNo = strlen($cekSebelum->No_Register);
            $noSurat = substr($cekSebelum->No_Register, $panjanNoAwal,$panjanNo);
          }else{
            $regisSebelum = Rsu_Register::where('No_RM', $request->rm)->orderby('Tgl_Register','DESC')->first();
            $panjanNoAwal = strlen($regisSebelum->No_Register) - 6;
            $panjanNo = strlen($regisSebelum->No_Register);
            $noSurat = substr($regisSebelum->No_Register, $panjanNoAwal,$panjanNo);
          }
          $data['kodeDPJP'] = $kodeDPJP;
          $data['namaDPJP'] = $namaDPJP;
          $data['riwayatRegis'] = $riwayat;
          $data['noSurat'] = $noSurat;
        }
        $data['dokterBridgs'] = rsu_dokter_bridging::all();

        /* CARI DPJP LAYAN MENGACU DARI POLI RUJUK PASIEN */
        if (!isset($listDpjp['response']->list) && isset($data['rujukan']['response']->rujukan->poliRujukan)) {
          $urlDpjp = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/referensi/dokter/pelayanan/".$request->rawat."/tglPelayanan/".date('Y-m-d')."/Spesialis/".$data['rujukan']['response']->rujukan->poliRujukan->kode; // url develop
          // $urlDokter = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/referensi/dokter/pelayanan/".$request->rawat."/tglpelayanan/".date('Y-m-d')."/Spesialis/".$cekSebelum->poli_bpjs; // url rilis
          $resultDpjp = Requestor::set_new_curl_bridging($urlDpjp, $params, $method, $consID, $secretKey, $port,'','');

          $listDpjp = [
            'metaData' => json_decode($resultDpjp['metaData']),
            'response' => json_decode($resultDpjp['response']),
          ];
        }

        $data['dpjp'] = $listDpjp['response']->list;
        $return = ['status' => 'success', 'message' => 'Rujukan Ditemukan !!', 'data' => $data];
      }else{
        $data['dokterBridgs'] = rsu_dokter_bridging::all();
        $data['dpjp'] = isset($listDpjp['response']->list) ? $listDpjp['response']->list : null ;
        return ['status' => 'error', 'message' => 'Rujukan Tidak Ditemukan !!','data'=>$data];
      }
      return $return;
    }
  }

  public function getDokterDpjp(Request $request)
  {
    // return $request->all();
    $dokter = rsu_dokter_bridging::find($request->idDokterBridg);
    $urlDokter = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/referensi/dokter/pelayanan/2/tglpelayanan/".date('Y-m-d')."/Spesialis/".$dokter->polibpjs; // url Develop
    // $urlDokter = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/referensi/dokter/pelayanan/2/tglpelayanan/".date('Y-m-d')."/Spesialis/".$dokter->polibpjs; // url rilis
    $consID     = "21095"; //customer ID RS
    $secretKey  = "rsud6778ws122mjkrt"; //secretKey RS
    $method = 'GET';
    $port = '80';
    $params = '';
    $resultDokter = Requestor::set_new_curl_bridging($urlDokter, $params, $method, $consID, $secretKey, $port,'','');
    $hslDokter = [
      'metaData' => json_decode($resultDokter['metaData']),
      'response' => json_decode($resultDokter['response']),
    ];
    $kodeDPJP = '';
    $namaDPJP = '';
    // if ($hslDokter['response'] == null) {
    //   return ['status' => 'error', 'message' => $hslDokter['metaData']->message, 'data' => ''];
    // }
    foreach ($hslDokter['response']->list as $h) {
      if (strtolower($h->nama) == strtolower($dokter->dokter)) {
        $kodeDPJP = $h->kode;
        $namaDPJP = $h->nama;
      }
    }
    $data['kodeDPJP'] = $kodeDPJP;
    $data['namaDPJP'] = $namaDPJP;
    return ['status' => 'success', 'message' => 'Dokter Ditemukan !!', 'data' => $data];
  }

  public function getNoSurat(Request $request)
  {
    $cekSebelum = Rsu_RiwayatRegistrasi::where('no_rujukan', $request->noRujuk)
    ->orderBy('id_riwayat_regis','DESC')
    ->first();
    if (!empty($cekSebelum)) {
      $panjanNoAwal = strlen($cekSebelum->No_Register) - 6;
      $panjanNo = strlen($cekSebelum->No_Register);
      $noSurat = substr($cekSebelum->No_Register, $panjanNoAwal,$panjanNo);
    }else{
      $regisSebelum = Rsu_Register::where('No_RM', $request->rm)->orderby('Tgl_Register','DESC')->first();
      $panjanNoAwal = strlen($regisSebelum->No_Register) - 6;
      $panjanNo = strlen($regisSebelum->No_Register);
      $noSurat = substr($regisSebelum->No_Register, $panjanNoAwal,$panjanNo);
    }
    // $data['noSurat'] = $noSurat;
    return ['status' => 'success', 'message' => 'No SKDP Berhasil dibuat !!', 'noSurat' => $noSurat];
  }

  public function deleteSEP(Request $request)
  {
    return $request->all();
  }

  /*
  =================================================================================================================================================================
  =================================================================================================================================================================
  ======================================================================= PERSEtUJUAN SEP =========================================================================
  =================================================================================================================================================================
  =================================================================================================================================================================
  */

  public function main_persetujuan_sep(Request $request)
  {
    $this->data['classtutup'] = 'sidebar-collapse';
    //db lokal site
    /*$this->data['poli'] = Poli::all();
    $this->data['jenispasien'] = Setupall::where('groups','asuransi')->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])->get();*/

    //db rsu
    $this->data['poli'] = rsu_poli::all();
    $this->data['jenispasien'] = Rsu_setupall::where('groups','asuransi')->whereNotIn('nilaichar',['UMUM','JAMKESDA','PT POS','PT MENTARI','ACC DIREKTUR','JAMKESMAS','JAMSOSTEK','PT KAI','INHEALTH','PERUSAHAAN','JAMKESPROV','JASA RAHARJA','JKD KAB MJK','RENC BPJS','AMBIL OBAT'])->get();

    return view('Admin.bridging.persetujuanSEP.main')->with('data', $this->data);
  }

  public function create_persetujuan_sep(Request $request)
  {
    $this->data['classtutup'] = 'sidebar-collapse';

    $content = view('Admin.bridging.persetujuanSEP.form')->with('data', $this->data)->render();
    return ['status'=>'success','content'=>$content];

  }

  //Simpan
  public function simpanPengajuanSEP(Request $request){
    // return $request->all();
    $url = "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/Sep/pengajuanSEP"; //url web dev service bpjs
    // $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Sep/updtglplg"; //url web rilis baru service bpjs
    // $url = "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest/Sep/updtglplg"; //url web service bpjs
    $consID     = "21095"; //customer ID RS
    $secretKey  = "rsud6778ws122mjkrt"; //secretKey RS
    $method = 'POST';
    $port = '80';

    $namauser = Auth::user()->name_user;
    $data =array(
      "t_sep"=> [
        "noKartu"=> $request->noka,   //
        "tglSep"=> date('Y-m-d', strtotime($request->tgl)),
        "jnsPelayanan"=> $request->jenpel,
        "jnsPengajuan"=> $request->flag,
        "keterangan"=> $request->keterangan,
        "user" => $namauser,
        ],
      );
    // return $data;
    $params = json_encode(array('request' => $data));

    $result = Requestor::set_new_curl_bridging($url, $params, $method, $consID, $secretKey, $port,'',''); // bridging data peserta bpjs
    if ($result === false) {
      echo "Tidak dapat menyambung ke server";
    } else {
      $result = [
        'metaData' => json_decode($result['metaData']),
        'response' => json_decode($result['response']),
      ];
      if ($result['metaData']->code == 200) {
        $newdata = New VclaimPengajuanSep;
        $newdata->noKartu = $request->noka;
        $newdata->tglSep = date('Y-m-d', strtotime($request->tgl));
        $newdata->jnsPelayanan = $request->jenpel;
        $newdata->jnsPengajuan = $request->flag;
        $newdata->keterangan = $request->keterangan;
        $newdata->user = $namauser;
        $newdata->save();
        return $result;
      }else {
        return $result;
      }
    }
  }

  //======= Get List Pengajuan =========//
  public function get_list_pengajuan(Request $request)
  {
    // return $request->all();
    $data = VclaimPengajuanSep::whereYear('tglSep', '=', $request->tahun)
                            ->whereMonth('tglSep', '=', $request->bulan)
                            ->get();
    if (count($data) != 0) {
      $return = ['code'=>'200','status'=>'Data Ditemukan','data'=>$data];
    }else {
      $return = ['code'=>'500','status'=>'Data Tidak Ditemukan','data'=>''];
    }
    return response()->json($return);
  }
  /*
  =================================================================================================================================================================
  =================================================================================================================================================================
  ===================================================================== END PERSEtUJUAN SEP =======================================================================
  =================================================================================================================================================================
  =================================================================================================================================================================
  */
}
