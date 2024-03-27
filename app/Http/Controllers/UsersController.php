<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Users;
use App\Http\Libraries\compressFile;
use File, Auth, Redirect, Validator;

class UsersController extends Controller
{
    public function main(Request $request)
    {
        $this->data['classtutup'] = ' sidebar-collapse';
        return view('Admin.users.main')->with('data', $this->data);
    }

    public function datagrid(Request $request)
    {
        $data = Users::getJson($request);
        return response()->json($data);
    }

    public function formAdd(Request $request)
    {
        $content = view('Admin.users.formAdd')->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function AddUsers(Request $request)
    {
        $user = new Users;
        $user->email = $request->email;
        $user->password = bcrypt($request->email);
        $user->level = '1';
        $user->name_user = $request->name_user;
        $user->alias = $request->alias;
        $user->phone = $request->phone;
        $user->address_user = $request->address_user;
        $user->active = $request->active;
        if (!empty($request->photo_user)) {
            date_default_timezone_set('Asia/Jakarta');
            $ukuranFile = filesize($request->photo_user);
            if ($ukuranFile <= 500000) {
                $ext_foto = $request->photo_user->getClientOriginalExtension();
                $filename = "User_".date('Ymd-His')."_".$request->alias.".".$ext_foto;
                $temp_foto = 'uploads/users/';
                $proses = $request->photo_user->move($temp_foto, $filename);
                $user->photo_user = $filename;
            }else{
                $file=$_FILES['photo_user']['name'];
                if(!empty($file)){
                    $direktori="uploads/users/"; //tempat upload foto
                    $name='photo_user'; //name pada input type file
                    $namaBaru="User_".date('Ymd-His')."_".$request->alias; //name pada input type file
                    $quality=50; //konversi kualitas gambar dalam satuan %
                    $upload = compressFile::UploadCompress($namaBaru,$name,$direktori,$quality);
                }
                $ext_foto = $request->photo_user->getClientOriginalExtension();
                $user->photo_user = $namaBaru.".".$ext_foto;
            }
        }
        $user->save();

        if ($user) {
            return Redirect::route('users');
        }else{
            return redirect()->route('users')->with('title', 'Maaf !!')->with('message', 'User Gagal Ditambahkan')->with('type', 'error');
        }
    }

    public function formUpdate(Request $request)
    {
        $data['user'] = Users::find($request->id);
        $content = view('Admin.users.formUpdate',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function UpdateUsers(Request $request)
    {
        $user = Users::find($request->id);
        $user->email = $request->email;
        $user->password = bcrypt($request->email);
        $user->level = '1';
        $user->name_user = $request->name_user;
        $user->alias = $request->alias;
        $user->phone = $request->phone;
        $user->address_user = $request->address_user;
        $user->active = $request->active;
        if (!empty($request->photo_user)) {
            date_default_timezone_set('Asia/Jakarta');
            $ukuranFile = filesize($request->photo_user);
            if ($ukuranFile <= 500000) {
                $ext_foto = $request->photo_user->getClientOriginalExtension();
                $filename = "User_".date('Ymd-His')."_".$request->alias.".".$ext_foto;
                $temp_foto = 'uploads/users/';
                $proses = $request->photo_user->move($temp_foto, $filename);
                $user->photo_user = $filename;
            }else{
                $file=$_FILES['photo_user']['name'];
                if(!empty($file)){
                    $direktori="uploads/users/"; //tempat upload foto
                    $name='photo_user'; //name pada input type file
                    $namaBaru="User_".date('Ymd-His')."_".$request->alias; //name pada input type file
                    $quality=50; //konversi kualitas gambar dalam satuan %
                    $upload = compressFile::UploadCompress($namaBaru,$name,$direktori,$quality);
                }
                $ext_foto = $request->photo_user->getClientOriginalExtension();
                $user->photo_user = $namaBaru.".".$ext_foto;
            }
        }
        $user->save();

        if ($user) {
            return Redirect::route('users');
        }else{
            return redirect()->route('users')->with('title', 'Maaf !!')->with('message', 'User Gagal Diperbaharui')->with('type', 'error');
        }
    }

    public function resetSandi(Request $request)
    {
        $user = Users::find($request->id);
        $user->password = bcrypt($user->email);
        $user->save();

        if ($user) {
            return ['status' => 'success', 'message' => 'Password Successfully Reset !!'];
        } else {
            return ['status'=>'error', 'message'=>'Password Failed to Reset !!'];
        }
    }

    public function delete(Request $request)
    {
        $user = Users::find($request->id);
        if(!empty($user)){
            $nama_file = $user->photo_user;
            $user->delete();
            if ($nama_file != null) {
                File::delete('uploads/users/'.$nama_file);
            }
            return ['status' => 'success', 'message' => 'Data berhasil dihapus'];
        }else{
            return ['status'=>'error', 'message'=>'Invalid user.'];
        }
    }
}
