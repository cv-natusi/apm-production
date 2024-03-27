<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Antrian</title>
    <style>
        table.printTable td {
            font-size: 8pt;
        }
        table.printTable2 td {
            font-size: 8pt;
        }
        @page {
            size: 80mm 148mm;
            margin: 0;
        }
        @media print {
            table.printTable td {
                font-size:8pt; 
            }
            table.printTable2 td {
                font-size:8pt; 
            }
            html,body {
                -webkit-print-color-adjust: exact;
                width: auto;
                margin-top: 15px;
                margin-left: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="printableArea">
                <div class="row">
                    <table class="printTable" style="width:100%">
                        <tr>
                            <td style="width: 31%; font-weight: bold">Nama</td>
                            <td style="width: 69%;">: {{$data['getAntrian']->tm_customer->NamaCust}} ({{$data['getAntrian']->tm_customer->JenisKel}})</td>
                        </tr>
                        <tr>
                            <td style="width: 31%; font-weight: bold">Alamat</td>
                            <td style="width: 69%;">: {{$data['getAntrian']->tm_customer->Alamat}}</td>
                        </tr>
                        <tr>
                            <td style="width: 31%; font-weight: bold">No. RM</td>
                            <td style="width: 69%;">: {{$data['getAntrian']->no_rm}}</td>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <hr style="width: 90%; margin-left:0;">
                    <hr style="width: 90%; margin-left:0;">
                </div>
                <div class="row">
                    <table class="printTable2" style="width:100%">
                        <tr>
                            <td style="width: 30%; font-weight: bold;">Tgl. Periksa</td>
                            <td style="width: 70%;">: {{$data['getAntrian']->tgl_periksa}}</td>
                        </tr>
                        <tr>
                            <td style="width: 30%; font-weight: bold;">Poli Tujuan</td>
                            <td style="width: 70%;">: {{$data['getAntrian']->mapping_poli_bridging->tm_poli->NamaPoli}}</td>
                        </tr>
                        <tr>
                            <td style="width: 30%; font-weight: bold;">No. Daftar</td>
                            <td style="width: 70%;">: {{$data['getAntrian']->no_antrian}}</td>
                        </tr>
                        <tr>
                            <td style="width: 30%; font-weight: bold;">No. Antrian</td>
                            <td style="width: 70%;">: {{$data['getAntrian']->nomor_antrian_poli}}</td>
                        </tr>
                    </table>
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
        });
    </script>
</body>
</html>