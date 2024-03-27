<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Requestor;
use App\Http\Libraries\compressFile;
use App\Http\Models\Identity;
use App\Http\Models\Loket;
use App\Http\Models\Poli;
use App\Http\Models\Customer;
use App\Http\Models\Register;
use App\Http\Models\Rawatjalan;
use App\Http\Models\CC;
use App\Http\Models\Rsu_Register;
use App\Http\Models\Rsu_Rawatjalan;
use App\Http\Models\Rsu_RawatJalanObat;
use App\Http\Models\Rsu_RawatJalanTindakan;
use App\Http\Models\Rsu_cc;
use Redirect, File, Auth, DB;
class APILaboratController extends Controller
{
    function main(Request $request){
        // return $request->all();
        $rm = $request->rm;
        $rm =  strtoupper($rm);
        $cur_page = $request->page;
        $per_page = 10;
        $jumlah_halaman = 0;
        $offset = ($per_page*$cur_page);


        $dbcon = pg_connect("host='192.168.1.172' user='postgres' password='postgres5432' dbname='lims2'");

        // Cek Koneksi Ke Server Database
        // PAKAI
        $query = "
        SELECT a.nama as nama_pasien,a.periksa_tgl,a.info1,
        b.satuan,b.metoda,b.merk_alat,b.kode_alat,b.hasil,
        c.nama as nama_lab
        FROM tab_lab_master as a 
        LEFT JOIN tab_lab_detil as b ON b.id_master=a.id 
        LEFT JOIN tab_kdlab as c ON c.id=b.id_lab
        WHERE a.no_rm = '$rm'
        ORDER BY a.periksa_tgl DESC
        LIMIT $per_page
        OFFSET $offset
        ";

        $query_total = "
        SELECT a.nama as nama_pasien,a.periksa_tgl,a.info1,
        b.satuan,b.metoda,b.merk_alat,b.kode_alat,b.hasil,
        c.nama as nama_lab
        FROM tab_lab_master as a 
        LEFT JOIN tab_lab_detil as b ON b.id_master=a.id 
        LEFT JOIN tab_kdlab as c ON c.id=b.id_lab
        WHERE a.no_rm = '$rm'
        ORDER BY a.periksa_tgl DESC
        ";
        // END PAKAI

        // $query = "
        // SELECT a.nama as nama_pasien,a.periksa_tgl,a.info1,
        // b.satuan,b.metoda,b.merk_alat,b.kode_alat,b.hasil,
        // c.nama as nama_lab
        // FROM tab_lab_master as a 
        // LEFT JOIN tab_lab_detil as b ON b.id_master=a.id 
        // LEFT JOIN tab_kdlab as c ON c.id=b.id_lab
        // ORDER BY a.periksa_tgl DESC
        // LIMIT 1
        // ";

        $result = pg_query($dbcon, $query) or die("Query gagal");
        $result_total = pg_query($dbcon, $query_total) or die("Query gagal");
        $arr_pakai = [];
        $total = pg_num_rows($result_total);
        $status = 'error';
        $message = 'data tidak ada';
        $code = '300';
        if($total!=0){
            $jumlah_halaman = ceil($total/$per_page);
            while($arr = pg_fetch_assoc ($result)){
                $arr['nama_pasien'] = str_replace("  ", "", $arr['nama_pasien']);
                $arr['info1'] = str_replace("  ", "", $arr['info1']);
                $arr['satuan'] = str_replace("  ", "", $arr['satuan']);
                $arr['metoda'] = str_replace("  ", "", $arr['metoda']);
                $arr['nama_lab'] = str_replace("  ", "", $arr['nama_lab']);
                $arr['hasil'] = str_replace("  ", "", $arr['hasil']);
                $arr_pakai[] = $arr;
            }
            $status='success';
            $message='data ada';
            $code='200';
        }

        return ['status'=>$status,'code'=>$code,'message'=>$message,'data'=>$arr_pakai,'current_page'=>$cur_page,'jumlah_page'=>$jumlah_halaman];
    }
}
