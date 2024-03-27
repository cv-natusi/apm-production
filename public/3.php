<?php
header("Content-Type: text/plain");
$data = json_decode(file_get_contents('php://input'),true);
file_put_contents('listen.txt', print_r($data,1));
// echo "UNDER MAINTENANCE";
$msg = $data['message'];
echo "Pesan anda : $msg";