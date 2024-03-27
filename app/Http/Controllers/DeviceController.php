<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Device;
use File, Auth, Redirect, Validator;

class DeviceController extends Controller
{
    public function main(Request $request)
    {
        $this->data['classtutup'] = '';
        return view('Admin.Device.main')->with('data', $this->data);
    }

    public function datagrid(Request $request)
    {
        $data = Device::getJson($request);
        return response()->json($data);
    }

    public function accepteds(Request $request)
    {
        $device = Device::find($request->id);
        $device->accepted = '1';
        $device->save();

        if($device){
            return ['status' => 'success', 'message' => 'Device Berhasil di Verifikasi'];
        }else{
            return ['status'=>'error', 'message'=>'Invalid user.'];
        }
    }

    public function blockeds(Request $request)
    {
        $device = Device::find($request->id);
        $device->accepted = '0';
        $device->save();

        if($device){
            return ['status' => 'success', 'message' => 'Device Berhasil di Block'];
        }else{
            return ['status'=>'error', 'message'=>'Invalid user.'];
        }
    }

    public function reset(Request $request)
    {
        $device = Device::find($request->id);
        $device->imei = '';
        $device->save();

        if($device){
            return ['status' => 'success', 'message' => 'Data berhasil direset'];
        }else{
            return ['status'=>'error', 'message'=>'Invalid user.'];
        }
    }

    public function delete(Request $request)
    {
        $device = Device::find($request->id);
        if(!empty($device)){
            $device->delete();
            return ['status' => 'success', 'message' => 'Data berhasil dihapus'];
        }else{
            return ['status'=>'error', 'message'=>'Invalid user.'];
        }
    }
}
