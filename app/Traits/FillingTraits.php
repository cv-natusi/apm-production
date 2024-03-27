<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait FillingTraits {

    public function generateNoRegistrasi()
    {
        try {
            $filter = date('y') . "200";
            $filtered = DB::connection('dbrsud')->table('tr_registrasi')
                ->where('No_Register', 'like', '%' . $filter . '%')
                ->orderBy('No_Register', 'DESC')
                ->first();
    
            if(!empty($filtered)){
                $unik = (int) substr($filtered->No_Register,5) + 1;
                $unik = STR_PAD($unik, 5, '0', STR_PAD_LEFT);
    
                return $filter . $unik;
            }else{
                $unik = STR_PAD('1', 5, '0', STR_PAD_LEFT);
    
                return $filter . $unik;
            }
        } catch (\Exception $e) {
            Log::info('generateNoRegistrasi - error : ' . $e->getMessage());
            abort(500, 'Filling, Generate Nomor Registrasi Gagal');
        }
    }

    public function dataCustomer(String $noRm)
    {
        try {
            $dataCustomer = DB::connection('dbrsud')->table('tm_customer')
                ->where('KodeCust', $noRm)
                ->first();

            return $dataCustomer;
        } catch (\Exception $e) {
            Log::info('dataCustomer - error : ' . $e->getMessage());
            abort(500, 'Filling, Data Customer('.$noRm.') Tidak Ditemukan');
        }
    }

    public function fillingPayload(Object $data)
    {
        $payload = [
            'no_rm' => $data->no_rm,
            'tgl_periksa' => $data->tanggalPeriksa,
        ];

        return $payload;
    }

    public function registrasiPayload(Object $dataCust, Object $dataFil)
    {
        $tanggal = date('Y-m-d', strtotime($dataFil->tanggalPeriksa));
        $tanggalRegister = $tanggal . " " . date('H:i:s');

        $payload = [
            'TransReg' => "RE",
            'No_Register' => $this->generateNoRegistrasi(),
            'Tgl_Register' => date('Y-m-d H:i:s', strtotime($tanggalRegister) ),
            'Jam_Register' => date('H:i:s'),
            'No_RM' => $dataCust->KodeCust,
            'Nama_Pasien' => $dataCust->NamaCust,
            'AlamatPasien' => $dataCust->Alamat,
            'Umur' => isset($dataCust->umur)?$dataCust->umur:"37",
            "Kode_Ass" => null,
            "Kode_Poli1" => $dataFil->kodePoli,
            "JenisKel" => $dataCust->JenisKel,
            "Rujukan" => null,
            "NoSEP" => null,
            "NoPeserta" => isset($dataFil->nomor_bpjs)?$dataFil->nomor_bpjs:0000000000000,
            "Biaya_Registrasi" => 0,
            "Status" => 'Belum Dibayar',
            "NamaAsuransi" => $dataFil->caraBayar,
            "Japel" => 0,
            "JRS" => 0,
            "TipeReg" => 'REG',
            "SudahCetak" => 'N',
            "BayarPendaftaran" => 'N',
            "Tgl_Lahir" => date('Y-m-d', strtotime($dataCust->TglLahir)),
            "isKaryawan" => 'N',
            "isProcessed" => 'N',
            "isPasPulang" => 'N',
            "Jenazah" => 'N'
        ];

        return $payload;
    }
}