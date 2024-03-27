<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Antrian</title>
    <style>
        @page {
            size: 80mm 148mm;
            margin: 0;
        }
        @media print {
            html,body {
                -webkit-print-color-adjust: exact;
                width: auto;
                margin-left: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="row" style="display: flex; justify-content: center;">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <img style="max-width: 70%; height: auto;" src="{{ url('uploads/identitas') }}/{!! $data['identitas']->logo_kiri !!}" class="logoLeftRegistration" alt="Retro HTML5 template">
                </div>
            </div>
            <div class="row" style="margin-top: 15px;">
                <div class="col-md-12">
                    <span style="font-size: 10pt;" id="date"></span><br>
                    <span style="font-size: 10pt;" id="time"></span>
                </div>
            </div>
            <div class="row" style="margin-top: 15px; text-align: center;">
                <div class="col-md-12">
                    <span style="font-size: 6pt"><b>SELAMAT DATANG</b></span><br>
                    <span style="font-size: 6pt"><b>DI RSUD Dr. WAHIDIN SUDIRO HUSODO KOTA MOJOKERTO</b></span>
                </div>
            </div>
            <div class="row" style="text-align:center; margin-top: 10px;">
                <div class="col-lg-12" style="background: #F2D9D9; height: 20px;">
                    <span style="font-size: 8pt;"><b>NOMOR ANTRIAN {{$for}}</b></span><br>
                </div>
            </div>
            <div class="row" style="text-align:center;">
                <div class="col-md-12">
                        <span style="font-size: 8pt;">Anda Adalah Pasien {{($antrian->is_pasien_baru == 'Y' ? 'BARU' : 'LAMA')}}</span><br>
                        <span style="font-size: 30pt;"><b>{{$noAntrian}}</b></span><br>
                        <span style="font-size: 8pt;">Tujuan : {{$antrian->mapping_poli_bridging->tm_poli->NamaPoli}}</span><br>
                        <span style="font-size: 8pt;">Pembayaran : {{$antrian->jenis_pasien}}</span><br>
                        <span style="font-size: 8pt;">Silahkan menunggu panggilan di</span><br>
                        <span style="font-size: 8pt;">{{$tujuan}}</span>
                </div>
            </div>
            <div class="row" style="margin-top: 15px; text-align:center;">
                <div class="col-md-12" style="background: rgba(0, 0, 0, 0.3); height: 20px;">
                    <span style="font-size: 8pt;"><b>" KEPUASAN PASIEN TUJUAN KAMI "</b></span>
                </div>
            </div>
        </div>
    </div>

    <!-- javascript -->
    <script src="{!! url('assetsite/js/jquery-1.8.2.min.js" type="text/javascript') !!}"></script> 
    <script src="{!! url('assetsite/js/bootstrap.min.js" type="text/javascript') !!}"></script> 
    <script type="text/javascript">
        $(document).ready(function () {
            setTimeout(function () {
                window.focus()
                window.print()
                window.close()
            }, 1000)
            arrbulan = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
            date = new Date();
            millisecond = date.getMilliseconds();
            detik = date.getSeconds();
            menit = date.getMinutes();
            jam = date.getHours();
            hari = date.getDay();
            tanggal = date.getDate();
            bulan = date.getMonth();
            tahun = date.getFullYear();
            // document.write(tanggal+"-"+arrbulan[bulan]+"-"+tahun+"<br/>"+jam+" : "+menit+" : "+detik+"."+millisecond);

            $('#date').html(tanggal+" "+arrbulan[bulan]+" "+tahun)
            $('#time').html((jam<10?'0':'')+jam+" : "+(menit<10?'0':'')+menit+" : "+(detik<10?'0':'')+detik+" "+"WIB")
        });
    </script>
</body>
</html>