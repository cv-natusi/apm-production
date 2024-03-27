<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Http\Libraries\Notifikasi;

class ReminderControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:control';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder Control';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = date("Y-m-d");
        $kueri = "SELECT id_rekapMedik,no_RM,no_Register,tanggalKunjungan,NamaPoli,tgl_kontrol FROM rekap_medik WHERE tgl_kontrol = '".$date."'";

        $arrPakai = [];
        $playerIDPakai = [];
        $getData = DB::connection('dbwahidin')->select($kueri);

        if($getData){
            foreach($getData as $row){
                $arrPakai[] = $row->no_RM;
            }

            $cekCustDevice = Device::whereIn('KodeCust', $arrPakai)->get();
            foreach($cekCustDevice as $pId){
                $playerIDPakai[] = $pId->player_id;
            }
            // return $playerIDPakai;
            // push notif
            $input = [
                'judul'=>'Pengingat Kontrol',
                'data'=>[
                    'status'=>'success',
                    'code'=>'200',
                    'message'=>'Hari ini ada jadwal kontrol',
                    'data'=>$row,
                ],
                'id_player'=>$playerIDPakai,
            ];

            // return $input;
            Notifikasi::notifikasi($input);
        }
    }
}
