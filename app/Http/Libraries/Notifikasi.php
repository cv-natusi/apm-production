<?php 
namespace App\Http\Libraries;
class Notifikasi   {
  public static function notifikasi($input){
    $content = array(
      "en" => $input['data']['message'] //judul notifikasi
    );
    $headings = array(
      "en" => $input['judul']
    );
    $fields = array(
      'app_id' => "e7e58ca2-664d-4a66-87e7-dda312268270", //App id onesignal
      'include_player_ids' => $input['id_player'], //ID Player penerima
      // 'include_player_ids' => 'cd21a734-1256-488a-96fb-799dcc336aca',
      'headings' => $headings,
      // 'included_segments' => array('Active Users'),
      // 'filters' => array(array("field" => "subscribed", "relation" => "=", "value" => true),array("operator" => "AND"),array("field" => "external_id", "relation" => "!=", "value" => null)),
      'data' =>$input['data'],
      'android_channel_id' => "87415915-d9c1-4345-bd6d-ad8e167283b9",
      'content_available' => true,
      'contents' => $content
    );
    $fields = json_encode($fields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));//Authorized onesignal
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Authorization: Basic MjFiMzM3MGMtMTE0ZS00ZTAwLTllZmUtZjUyN2FiODJiNGIx'));//Authorized onesignal
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch);
    curl_close($ch);
    // return $response;
  }
}