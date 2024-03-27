<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Libraries\compressFile;
use App\Http\Models\Identity;
use App\Http\Models\Menus;
use App\Http\Models\Slider;
use App\Http\Models\Fitur;
use App\Http\Models\Berita;
use App\Http\Models\Berita_pilihan;
use App\Http\Models\Users;
use App\Http\Models\Iklan;
use App\Http\Models\Galeri;
use App\Http\Models\Amtv;
use App\Http\Models\Exkul;
use App\Http\Models\Dokter;
use App\Http\Models\Tags;
use App\Http\Models\Video;
use App\Http\Models\KataJorok;
use Redirect, File, Sentinel;
class IdentitasController extends Controller
{
    /* =========================================================
    ===================== IDENTITAS ============================
    ========================================================= */
    public function identitas(Request $request)
    {
        $this->data['classtutup'] = '';
        $this->data['title'] = 'Identitas';
        return view('Admin.identitas.main')->with('data', $this->data);
    }
    public function changeIdentity(Request $request)
    {
        $identity = Identity::find(1);
        $identity->nama_web = $request->nama_web;
        $identity->alamat = $request->alamat;
        $identity->phone = $request->phone;
        $identity->email = $request->email;
        $identity->jam_operasional = $request->jam_operasional;
        if (!empty($request->icon)) {
            if(file_exists('uploads/identitas/'.$identity->favicon)){
                unlink('uploads/identitas/'.$identity->favicon);
            }
            $ext_foto = $request->icon->getClientOriginalExtension();
            $filename = "Icon_".date('dmY').".".$ext_foto;
            $temp_foto = 'uploads/identitas/';
            $proses = $request->icon->move($temp_foto, $filename);
            $identity->favicon = $filename;
        }
        if (!empty($request->logo_kiri)) {
            if(file_exists('uploads/identitas/'.$identity->logo_kiri)){
                unlink('uploads/identitas/'.$identity->logo_kiri);
            }
            $ext_logo = $request->logo_kiri->getClientOriginalExtension();
            $filenameLogo = "Logo_".date('dmY').".".$ext_logo;
            $temp_Logo = 'uploads/identitas/';
            $prosesLogo = $request->logo_kiri->move($temp_Logo, $filenameLogo);
            $identity->logo_kiri = $filenameLogo;
        }
        $identity->save();
        if ($identity) {
            return Redirect::route('identitas')->with('title', 'Success !')->with('message', 'Identitas Successfully Updated !!')->with('type', 'success');
        } else {
            return Redirect::route('identitas')->with('title', 'Whoops!!!')->with('message', 'Identitas Failed to Update !!')->with('type', 'error');
        }
    }
    /* =========================================================
    ======================== LOGO ==============================
    ========================================================= */
    public function logo(Request $request)
    {
        $data['mn_active'] = 'modulWeb';
        $data['title'] = 'Logo';
        $data['identity'] = Identity::find(1);
        return view('Admin.web.logo.main', $data);
    }
    public function formUpdateLogo(Request $request)
    {
        $data['identity'] = Identity::find(1);
        $data['position'] = $request->posisi;
        $content = view('Admin.web.logo.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }
    public function UpdateLogo(Request $request)
    {
        $identity = Identity::find(1);
        if ($request->position == "Kiri") {
            if (!empty($request->logo)) {
                if(file_exists('./uploads/identitas/'.$identity->logo_kiri)){
                    unlink('./uploads/identitas/'.$identity->logo_kiri);
                }
                // return "here";
                $ext_foto = $request->logo->getClientOriginalExtension();
                $filename = "Logo_Kiri".date('YmdHis').".".$ext_foto;
                $temp_foto = 'uploads/identitas/';
                $proses = $request->logo->move($temp_foto, $filename);
                $identity->logo_kiri = $filename;
            }
        }
        $identity->save();
        if ($identity) {
            return Redirect::route('logo')->with('title', 'Success !')->with('message', 'Logo Successfully Updated !!')->with('type', 'success');
        } else {
            return Redirect::route('logo')->with('title', 'Whoops!!!')->with('message', 'Logo Failed to Update !!')->with('type', 'error');
        }
    }


    /* =========================================================
    ======================== Foto Home ==============================
    ========================================================= */
    public function fotohome(Request $request)
    {
        $data['mn_active'] = 'modulWeb';
        $data['title'] = 'Foto Beranda';
        $data['identity'] = Identity::find(1);
        return view('Admin.web.foto_home.main', $data);
    }
    public function formUpdatefotohome(Request $request)
    {
        $data['identity'] = Identity::find(1);
        $content = view('Admin.web.foto_home.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }
    public function Updatefotohome(Request $request)
    {
        $identity = Identity::find(1);
        if (!empty($request->logo)) {
            if(file_exists('./uploads/identitas/'.$identity->foto_sejarah)){
                unlink('./uploads/identitas/'.$identity->foto_sejarah);
            }
            $ext_foto = $request->logo->getClientOriginalExtension();
            $filename = "foto_beranda".date('YmdHis').".".$ext_foto;
            $temp_foto = 'uploads/identitas/';
            $proses = $request->logo->move($temp_foto, $filename);
            $identity->foto_sejarah = $filename;
        }
        $identity->save();
        if ($identity) {
            return Redirect::route('fotohome')->with('title', 'Success !')->with('message', 'Foto Beranda Successfully Updated !!')->with('type', 'success');
        } else {
            return Redirect::route('fotohome')->with('title', 'Whoops!!!')->with('message', 'Foto Beranda Failed to Update !!')->with('type', 'error');
        }
    }

    /* =========================================================
    ======================== MENU ==============================
    ========================================================= */
    public function menu(Request $request)
    {
        $data['mn_active'] = 'modulWeb';
        $data['title'] = 'Menu';
        $data['menus'] = Menus::where('parent_id','0')->get();
        $data['childMenus'] = Menus::where('parent_id','!=','0')->get();
        return view('Admin.web.menu.main', $data);
    }
    public function formAddMenu(Request $request)
    {
        $content = view('Admin.web.menu.formAdd')->render();
        return ['status' => 'success', 'content' => $content];
    }
    public function AddMenu(Request $request)
    {
        $menu = new Menus;
        if($request->parent!='' && $request->parent_cek=='y'){
            $menu->parent_id = $request->parent;
        }else{
            $menu->parent_id = '0';
        }
        $menu->nama_menu = $request->nama_menu;
        $menu->aktif = $request->aktif;
        $menu->save();
        if ($menu) {
            return Redirect::route('menu')->with('title', 'Success !')->with('message', 'Menu Successfully Created !!')->with('type', 'success');
        } else {
            return Redirect::route('menu')->with('title', 'Whoops!!!')->with('message', 'Menu Failed to Create !!')->with('type', 'error');
        }
    }
    public function formUpdateMenu(Request $request)
    {
        $data['menu'] = Menus::find($request->id);
        $content = view('Admin.web.menu.formEdit', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }
    public function UpdateMenu(Request $request)
    {
        $menu = Menus::find($request->id_menu);
        if($request->parent!='' && $request->parent_cek=='y'){
            $menu->parent_id = $request->parent;
        }else{
            $menu->parent_id = '0';
        }
        $menu->nama_menu = $request->nama_menu;
        $menu->aktif = $request->aktif;
        $menu->save();
        if ($menu) {
            return Redirect::route('menu')->with('title', 'Success !')->with('message', 'Menu Successfully Updated !!')->with('type', 'success');
        } else {
            return Redirect::route('menu')->with('title', 'Whoops!!!')->with('message', 'Menu Failed to Update !!')->with('type', 'error');
        }
    }
    /* =========================================================
    ======================= SEJARAH ============================
    ========================================================= */
    public function sejarah(Request $request)
    {
        $data['mn_active'] = 'sekolah';
        $data['title'] = 'Sejarah';
        $data['identity'] = Identity::find(1);
        return view('Admin.web.sejarah.main', $data);
    }
    public function updateSejarah(Request $request)
    {

        $identity = Identity::find(1);
        $identity->sejarah = $request->sejarah_isi;
        $identity->save();

        if (!empty($request->foto_sejarah)) {
            if(file_exists('uploads/sejarah/'.$identity->foto_sejarah)){
                unlink('uploads/sejarah/'.$identity->foto_sejarah);
            }
            $ext_foto = $request->foto_sejarah->getClientOriginalExtension();
            $filename = "sejarah_".date('dmY')."_".date('Hmi').".".$ext_foto;
            $temp_foto = 'uploads/sejarah/';
            $proses = $request->foto_sejarah->move($temp_foto, $filename);
            $identity->foto_sejarah = $filename;
            $identity->save();
        }

        if ($identity) {
            return Redirect::route('sejarah')->with('title', 'Success !')->with('message', 'Sejarah Successfully Updated !!')->with('type', 'success');
        } else {
            return Redirect::route('sejarah')->with('title', 'Whoops!!!')->with('message', 'Sejarah Failed to Update !!')->with('type', 'error');
        }
    }
    /* =========================================================
    ======================= VISI MISI ============================
    ========================================================= */
    public function visimisi(Request $request)
    {
        $data['mn_active'] = 'sekolah';
        $data['title'] = 'Visi dan Misi';
        $data['identity'] = Identity::find(1);
        return view('Admin.web.visimisi.main', $data);
    }
    public function updateVisimisi(Request $request)
    {
        $identity = Identity::find(1);
        $identity->vm = $request->visimisi;
        $identity->save();
        if ($identity) {
            return Redirect::route('visimisi')->with('title', 'Success !')->with('message', 'Visi dan Misi Successfully Updated !!')->with('type', 'success');
        } else {
            return Redirect::route('visimisi')->with('title', 'Whoops!!!')->with('message', 'Visi dan Misi Failed to Update !!')->with('type', 'error');
        }
    }
    /* =========================================================
    ======================= UKS ============================
    ========================================================= */
    public function uks(Request $request)
    {
        $data['mn_active'] = 'sekolah';
        $data['title'] = 'Unit Kesehatan Sekolah';
        $data['uks'] = exkul::where('status_exkul','3')->first();;
        return view('Admin.web.uks.main', $data);
    }
    /* =========================================================
    ======================== KEPALA SEKOLAH =============================
    ========================================================= */
    public function kepsek(Request $request)
    {
        $data['mn_active'] = 'sekolah';
        $data['title'] = 'Sambutan Kepala Sekolah';
        $data['identity'] = Identity::find(1);
        return view('Admin.web.kepsek.main', $data);
    }
    public function updateKepsek(Request $request)
    {
        $identity = Identity::find(1);
        $identity->sambutan_kepsek = $request->kepsek;
        $foto = date('YmdHis');
        if (!empty($request->foto)) {
            /*if($identitas->foto_sambutan!=''){
                if(file_exists('uploads/identitas/'.$ekskul->foto)){
                    unlink('uploads/identitas/'.$ekskul->foto);
                }
            }*/
            $ext_foto = $request->foto->getClientOriginalExtension();
            $filename = 'foto_sambutan-'.$foto.'.'.$ext_foto;
            $temp_foto = 'uploads/identitas/';
            $proses = $request->foto->move($temp_foto, $filename);
            $identity->foto_sambutan = $filename;
        }
        $identity->save();
        if ($identity) {
            return Redirect::route('kepsek')->with('title', 'Success !')->with('message', 'Sambutan Successfully Updated !!')->with('type', 'success');
        } else {
            return Redirect::route('kepsek')->with('title', 'Whoops!!!')->with('message', 'Sambutan Failed to Update !!')->with('type', 'error');
        }
    }
    /* =========================================================
    ====================== STRUKTUR ORGANISASI ==========================
    ========================================================= */
    public function organisasi(Request $request)
    {
        $data['mn_active'] = 'sekolah';
        $data['title'] = 'Struktur Organisasi';
        $data['identity'] = Identity::find(1);
        return view('Admin.web.organisasi.main', $data);
    }

    public function formUpdateOrganisasi(Request $request)
    {
        $data['identity'] = Identity::find(1);
        $content = view('Admin.web.organisasi.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function updateOrganisasi(Request $request)
    {
        $identity = Identity::find(1);
        if (!empty($request->gambar)) {
            // if(file_exists('uploads/organisasi/'.$identity->gambar)){
            //     unlink('uploads/organisasi/'.$identity->fgambar);
            // }
            $ext_foto = $request->gambar->getClientOriginalExtension();
            $filename = "sejarah_".date('dmY')."_".date('Hmi').".".$ext_foto;
            $temp_foto = 'uploads/organisasi/';
            $proses = $request->gambar->move($temp_foto, $filename);
            $identity->struktur_organisasi = $filename;
            $identity->save();
        }
        $identity->save();
        if ($identity) {
            return Redirect::route('organisasi')->with('title', 'Success !')->with('message', 'Struktur Organisasi Successfully Updated !!')->with('type', 'success');
        } else {
            return Redirect::route('organisasi')->with('title', 'Whoops!!!')->with('message', 'Struktur Organisasi Failed to Update !!')->with('type', 'error');
        }
    }
    /* =========================================================
    ====================== SLIDER ==========================
    ========================================================= */
    public function slider(Request $request)
    {
        $data['mn_active'] = 'modulWeb';
        $data['title'] = 'Slider Gambar';
        $data['identity'] = Identity::find(1);
        return view('Admin.web.slider.main', $data);
    }

    public function tampilSlider(Request $request){
        $data = Slider::getSlider($request);
        return response()->json($data);
    }

    public function formUpdateSlider(Request $request)
    {
        $data['slider'] = Slider::find($request->id);
        $content = view('Admin.web.slider.formEdit',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function updateSlider(Request $request)
    {
        $slider = Slider::find($request->id_slider);
        $foto = date('YmdHis');
        if (!empty($request->logo)) {
            if($slider->gambar!=''){
                if(file_exists('uploads/slider/'.$slider->gambar)){
                    unlink('uploads/slider/'.$slider->gambar);
                }
            }
            $ext_foto = $request->logo->getClientOriginalExtension();
            $filename = 'Slider-'.$foto.'.'.$ext_foto;
            $temp_foto = 'uploads/slider/';
            $proses = $request->logo->move($temp_foto, $filename);
            $slider->gambar = $filename;
        }
        $slider->save();
        if ($slider) {
            return Redirect::route('slider')->with('title', 'Success !')->with('message', 'Slider Successfully Update !!')->with('type', 'success');
        } else {
            return Redirect::route('slider')->with('title', 'Whoops!!!')->with('message', 'Slider Failed to Update !!')->with('type', 'error');
        }
    }


/* ================================ Fitur =========================================== */
        public function fitur(Request $request)
        {
            $data['mn_active'] = 'modulWeb';
            $data['title'] = 'Fitur Aplikasi';
            $data['identity'] = Identity::find(1);
            return view('Admin.web.fitur.main', $data);
        }

        public function tampilfitur(Request $request){
            $data = Fitur::getIntro($request);
            return response()->json($data);
        }

        public function formUpdateFitur(Request $request)
        {
            $data['fitur'] = Fitur::find($request->id);
            $content = view('Admin.web.fitur.formEdit',$data)->render();
            return ['status' => 'success', 'content' => $content];
        }

        public function updateFitur(Request $request)
        {
            // return $request->all();
            $fitur = Fitur::find($request->id);
            $foto = date('YmdHis');
            $fitur->nama_fitur = $request->nama_fitur;
            $fitur->deskripsi = $request->deskripsi;
            $fitur->save();
            if ($fitur) {
                return Redirect::route('fitur')->with('title', 'Success !')->with('message', 'Slider Successfully Update !!')->with('type', 'success');
            } else {
                return Redirect::route('fitur')->with('title', 'Whoops!!!')->with('message', 'Slider Failed to Update !!')->with('type', 'error');
            }
        }
/* ================================ End Fitur =========================================== */


    /* =========================================================
    ====================== Fasilitas ==========================
    ========================================================= */
    public function fasilitas(Request $request)
    {
        $data['mn_active'] = 'sekolah';
        $data['title'] = 'Fasilitas';
        $data['identity'] = Identity::find(1);
        return view('Admin.web.ekskul.main', $data);
    }

    public function tampilFasilitaS(Request $request){
        $data = Exkul::getFasilitas($request);
        return response()->json($data);
    }

    public function formAddFasilitaS()
    {
        $content = view('Admin.web.ekskul.formAdd')->with('kategori','2')->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function formUpdateFasilitaS(Request $request)
    {
        $data['ekskul'] = Exkul::find($request->id);
        $data['kategori'] = '2';
        $content = view('Admin.web.ekskul.formEdit',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function uploadFasilitaS(Request $request)
    {
        $ekskul = new Exkul;
        $ekskul->nama_exkul = $request->nama_ekskul;
        $ekskul->deskripsi = $request->deskripsi;
        $ekskul->kategori = 2;
        $ekskul->status_exkul = $request->status;
        $foto = date('YmdHis');
        if (!empty($request->logo)) {
            if($ekskul->foto!=''){
                if(file_exists('uploads/ekskul/'.$ekskul->foto)){
                    unlink('uploads/ekskul/'.$ekskul->foto);
                }
            }
            $ext_foto = $request->logo->getClientOriginalExtension();
            $filename = 'Ekskul-'.$foto.'.'.$ext_foto;
            $temp_foto = 'uploads/exkul/';
            $proses = $request->logo->move($temp_foto, $filename);
            $ekskul->foto = $filename;
        }
        $ekskul->save();
        if ($ekskul) {
            return Redirect::route('fasilitas')->with('title', 'Success !')->with('message', 'Data Fasilitas Successfully Upload !!')->with('type', 'success');
        } else {
            return Redirect::route('fasilitas')->with('title', 'Whoops!!!')->with('message', 'Data Fasilitas Failed to Upload !!')->with('type', 'error');
        }
    }

    public function updateFasilitaS(Request $request)
    {
        $ekskul = Exkul::find($request->id_ekskul);
        $ekskul->nama_exkul = $request->nama_ekskul;
        $ekskul->deskripsi = $request->deskripsi;
        $ekskul->kategori = 2;
        $ekskul->status_exkul = $request->status;
        $foto = date('YmdHis');
        if (!empty($request->logo)) {
            if(file_exists('uploads/exkul/'.$ekskul->foto)){
                unlink('uploads/exkul/'.$ekskul->foto);
            }
            $ext_foto = $request->logo->getClientOriginalExtension();
            $filename = 'Ekskul-'.$foto.'.'.$ext_foto;
            $temp_foto = 'uploads/exkul/';
            $proses = $request->logo->move($temp_foto, $filename);
            $ekskul->foto = $filename;
        }
        $ekskul->save();
        if ($ekskul) {
            return Redirect::route('fasilitas')->with('title', 'Success !')->with('message', 'Data Fasilitas Successfully Updated !!')->with('type', 'success');
        } else {
            return Redirect::route('fasilitas')->with('title', 'Whoops!!!')->with('message', 'Data Fasilitas Failed to Update !!')->with('type', 'error');
        }
    }
    /* =========================================================
    ======================== Dokter ==========================
    ========================================================= */
    public function dokter(Request $request)
    {
        $data['mn_active'] = 'sekolah';
        $data['title'] = 'Dokter';
        $data['identity'] = Identity::find(1);
        return view('Admin.web.dokter.main', $data);
    }

    public function tampilDokter(Request $request){
        $data = Dokter::getDokter($request);
        return response()->json($data);
    }

    public function formAddDokter()
    {
        $content = view('Admin.web.dokter.formAdd')->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function formUpdateDokter(Request $request)
    {
        $data['ekskul'] = Exkul::find($request->id);
        $data['kategori'] = '2';
        $content = view('Admin.web.dokter.formEdit',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function uploadDokter(Request $request)
    {
        $ekskul = new Exkul;
        $ekskul->nama_exkul = $request->nama_ekskul;
        $ekskul->deskripsi = $request->deskripsi;
        $ekskul->kategori = 1;
        $ekskul->status_exkul = $request->status;
        $foto = date('YmdHis');
        if (!empty($request->logo)) {
            if($ekskul->foto!=''){
                if(file_exists('uploads/ekskul/'.$ekskul->foto)){
                    unlink('uploads/ekskul/'.$ekskul->foto);
                }
            }
            $ext_foto = $request->logo->getClientOriginalExtension();
            $filename = 'Ekskul-'.$foto.'.'.$ext_foto;
            $temp_foto = 'uploads/exkul/';
            $proses = $request->logo->move($temp_foto, $filename);
            $ekskul->foto = $filename;
        }
        $ekskul->save();
        if ($ekskul) {
            return Redirect::route('dokter')->with('title', 'Success !')->with('message', 'Data Dokter Successfully Upload !!')->with('type', 'success');
        } else {
            return Redirect::route('dokter')->with('title', 'Whoops!!!')->with('message', 'Data Dokter Failed to Upload !!')->with('type', 'error');
        }
    }

    public function updateDokter(Request $request)
    {
        $ekskul = Exkul::find($request->id_ekskul);
        $ekskul->nama_exkul = $request->nama_ekskul;
        $ekskul->deskripsi = $request->deskripsi;
        $ekskul->kategori = 1;
        $ekskul->status_exkul = $request->status;
        $foto = date('YmdHis');
        if (!empty($request->logo)) {
            if(file_exists('uploads/exkul/'.$ekskul->foto)){
                unlink('uploads/exkul/'.$ekskul->foto);
            }
            $ext_foto = $request->logo->getClientOriginalExtension();
            $filename = 'Ekskul-'.$foto.'.'.$ext_foto;
            $temp_foto = 'uploads/exkul/';
            $proses = $request->logo->move($temp_foto, $filename);
            $ekskul->foto = $filename;
        }
        $ekskul->save();

        if ($ekskul) {
            return Redirect::route('dokter')->with('title', 'Success !')->with('message', 'Data Dokter Successfully Updated !!')->with('type', 'success');
        } else {
            return Redirect::route('dokter')->with('title', 'Whoops!!!')->with('message', 'Data Dokter Failed to Update !!')->with('type', 'error');
        }
    }

    /* =========================================================
    ======================== UKP ==========================
    ========================================================= */
    public function ukp(Request $request)
    {
        $data['mn_active'] = 'layanan';
        $data['title'] = 'Layanan UKP';
        $data['identity'] = Identity::find(1);
        return view('Admin.web.ukp.main', $data);
    }

    public function tampilUkp(Request $request){
        $data = Exkul::getUkp($request);
        return response()->json($data);
    }

    public function formAddUkp()
    {
        $content = view('Admin.web.ukp.formAdd')->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function formUpdateUkp(Request $request)
    {
        $data['ekskul'] = Exkul::find($request->id);
        $content = view('Admin.web.ukp.formEdit',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function uploadUkp(Request $request)
    {
        $ekskul = new Exkul;
        $ekskul->nama_exkul = $request->nama_ekskul;
        $ekskul->deskripsi = $request->deskripsi;
        $ekskul->kategori = 3;
        $ekskul->status_exkul = $request->status;
        $foto = date('YmdHis');
        if (!empty($request->logo)) {
            if($ekskul->foto!=''){
                if(file_exists('uploads/ekskul/'.$ekskul->foto)){
                    unlink('uploads/ekskul/'.$ekskul->foto);
                }
            }
            $ext_foto = $request->logo->getClientOriginalExtension();
            $filename = 'Ekskul-'.$foto.'.'.$ext_foto;
            $temp_foto = 'uploads/exkul/';
            $proses = $request->logo->move($temp_foto, $filename);
            $ekskul->foto = $filename;
        }
        $ekskul->save();
        if ($ekskul) {
            return Redirect::route('ukp')->with('title', 'Success !')->with('message', 'Layanan UKP Successfully Upload !!')->with('type', 'success');
        } else {
            return Redirect::route('ukp')->with('title', 'Whoops!!!')->with('message', 'Layanan UKP Failed to Upload !!')->with('type', 'error');
        }
    }

    public function updateUkp(Request $request)
    {
        $ekskul = Exkul::find($request->id_ekskul);
        $ekskul->nama_exkul = $request->nama_ekskul;
        $ekskul->deskripsi = $request->deskripsi;
        $ekskul->kategori = 3;
        $ekskul->status_exkul = $request->status;
        $foto = date('YmdHis');
        if (!empty($request->logo)) {
            if(file_exists('uploads/exkul/'.$ekskul->foto)){
                unlink('uploads/exkul/'.$ekskul->foto);
            }
            $ext_foto = $request->logo->getClientOriginalExtension();
            $filename = 'Ekskul-'.$foto.'.'.$ext_foto;
            $temp_foto = 'uploads/exkul/';
            $proses = $request->logo->move($temp_foto, $filename);
            $ekskul->foto = $filename;
        }
        $ekskul->save();
        if ($ekskul) {
            return Redirect::route('ukp')->with('title', 'Success !')->with('message', 'Layanan UKP Successfully Updated !!')->with('type', 'success');
        } else {
            return Redirect::route('ukp')->with('title', 'Whoops!!!')->with('message', 'Layanan UKP Failed to Update !!')->with('type', 'error');
        }
    }

    /* =========================================================
    ======================== UKM DASAR =========================
    ========================================================= */
    public function ukmdasar(Request $request)
    {
        $data['mn_active'] = 'layanan';
        $data['title'] = 'Layanan UKM (Program Dasar)';
        $data['identity'] = Identity::find(1);
        return view('Admin.web.ukmdasar.main', $data);
    }

    public function tampilUkmDasar(Request $request){
        $data = Exkul::getUkmdasar($request);
        return response()->json($data);
    }

    public function formAddUkmdasar()
    {
        $content = view('Admin.web.ukmdasar.formAdd')->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function formUpdateUkmdasar(Request $request)
    {
        $data['ekskul'] = Exkul::find($request->id);
        $content = view('Admin.web.ukmdasar.formEdit',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function uploadUkmdasar(Request $request)
    {
        $ekskul = new Exkul;
        $ekskul->nama_exkul = $request->nama_ekskul;
        $ekskul->deskripsi = $request->deskripsi;
        $ekskul->kategori = 4;
        $ekskul->status_exkul = $request->status;
        $foto = date('YmdHis');
        if (!empty($request->logo)) {
            if($ekskul->foto!=''){
                if(file_exists('uploads/ekskul/'.$ekskul->foto)){
                    unlink('uploads/ekskul/'.$ekskul->foto);
                }
            }
            $ext_foto = $request->logo->getClientOriginalExtension();
            $filename = 'Ekskul-'.$foto.'.'.$ext_foto;
            $temp_foto = 'uploads/exkul/';
            $proses = $request->logo->move($temp_foto, $filename);
            $ekskul->foto = $filename;
        }
        $ekskul->save();
        if ($ekskul) {
            return Redirect::route('ukmdasar')->with('title', 'Success !')->with('message', 'Layanan UKM Program Dasar Successfully Upload !!')->with('type', 'success');
        } else {
            return Redirect::route('ukmdasar')->with('title', 'Whoops!!!')->with('message', 'Layanan UKM Program Dasar Failed to Upload !!')->with('type', 'error');
        }
    }

    public function updateUkmdasar(Request $request)
    {
        $ekskul = Exkul::find($request->id_ekskul);
        $ekskul->nama_exkul = $request->nama_ekskul;
        $ekskul->deskripsi = $request->deskripsi;
        $ekskul->kategori = 4;
        $ekskul->status_exkul = $request->status;
        $foto = date('YmdHis');
        if (!empty($request->logo)) {
            if(file_exists('uploads/exkul/'.$ekskul->foto)){
                unlink('uploads/exkul/'.$ekskul->foto);
            }
            $ext_foto = $request->logo->getClientOriginalExtension();
            $filename = 'Ekskul-'.$foto.'.'.$ext_foto;
            $temp_foto = 'uploads/exkul/';
            $proses = $request->logo->move($temp_foto, $filename);
            $ekskul->foto = $filename;
        }
        $ekskul->save();
        if ($ekskul) {
            return Redirect::route('ukmdasar')->with('title', 'Success !')->with('message', 'Layanan UKM Program Dasar Successfully Updated !!')->with('type', 'success');
        } else {
            return Redirect::route('ukmdasar')->with('title', 'Whoops!!!')->with('message', 'Layanan UKM Program Dasar Failed to Update !!')->with('type', 'error');
        }
    }

    /* =========================================================
    ======================== UKM Pengembangan =========================
    ========================================================= */
    public function ukmpengembang(Request $request)
    {
        $data['mn_active'] = 'layanan';
        $data['title'] = 'Layanan UKM (Program Pengembangan)';
        $data['identity'] = Identity::find(1);
        return view('Admin.web.ukmpengembang.main', $data);
    }

    public function tampilUkmPengembang(Request $request){
        $data = Exkul::getUkmpengembang($request);
        return response()->json($data);
    }

    public function formAddUkmPengembang()
    {
        $content = view('Admin.web.ukmpengembang.formAdd')->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function formUpdateUkmPengembang(Request $request)
    {
        $data['ekskul'] = Exkul::find($request->id);
        $content = view('Admin.web.ukmpengembang.formEdit',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function uploadUkmPengembang(Request $request)
    {
        $ekskul = new Exkul;
        $ekskul->nama_exkul = $request->nama_ekskul;
        $ekskul->deskripsi = $request->deskripsi;
        $ekskul->kategori = 5;
        $ekskul->status_exkul = $request->status;
        $foto = date('YmdHis');
        if (!empty($request->logo)) {
            if($ekskul->foto!=''){
                if(file_exists('uploads/ekskul/'.$ekskul->foto)){
                    unlink('uploads/ekskul/'.$ekskul->foto);
                }
            }
            $ext_foto = $request->logo->getClientOriginalExtension();
            $filename = 'Ekskul-'.$foto.'.'.$ext_foto;
            $temp_foto = 'uploads/exkul/';
            $proses = $request->logo->move($temp_foto, $filename);
            $ekskul->foto = $filename;
        }
        $ekskul->save();
        if ($ekskul) {
            return Redirect::route('ukmpengembang')->with('title', 'Success !')->with('message', 'Layanan UKM Program Pengembangan Successfully Upload !!')->with('type', 'success');
        } else {
            return Redirect::route('ukmpengembang')->with('title', 'Whoops!!!')->with('message', 'Layanan UKM Program Pengembangan Failed to Upload !!')->with('type', 'error');
        }
    }

    public function updateUkmPengembang(Request $request)
    {
        $ekskul = Exkul::find($request->id_ekskul);
        $ekskul->nama_exkul = $request->nama_ekskul;
        $ekskul->deskripsi = $request->deskripsi;
        $ekskul->kategori = 5;
        $ekskul->status_exkul = $request->status;
        $foto = date('YmdHis');
        if (!empty($request->logo)) {
            if(file_exists('uploads/exkul/'.$ekskul->foto)){
                unlink('uploads/exkul/'.$ekskul->foto);
            }
            $ext_foto = $request->logo->getClientOriginalExtension();
            $filename = 'Ekskul-'.$foto.'.'.$ext_foto;
            $temp_foto = 'uploads/exkul/';
            $proses = $request->logo->move($temp_foto, $filename);
            $ekskul->foto = $filename;
        }
        $ekskul->save();
        if ($ekskul) {
            return Redirect::route('ukmpengembang')->with('title', 'Success !')->with('message', 'Layanan UKM Program Pengembangan Successfully Updated !!')->with('type', 'success');
        } else {
            return Redirect::route('ukmpengembang')->with('title', 'Whoops!!!')->with('message', 'Layanan UKM Program Pengembangan Failed to Update !!')->with('type', 'error');
        }
    }
    /* =========================================================
    ======================== EDITOR ============================
    ========================================================= */
    public function editor(Request $request)
    {
        $data['mn_active'] = 'pengguna';
        $data['title'] = 'Pengguna';
        $data['users'] = Users::where('level','2')->get();
        return view('Admin.pengguna.editor.main', $data);
    }
    public function editorDatagrid(Request $request)
    {
        $data = Users::getJsonEditor($request);
        return response()->json($data);
    }
    public function formAddEditor(Request $request)
    {
        $content = view('Admin.pengguna.editor.formAdd')->render();
        return ['status' => 'success', 'content' => $content];
    }
    public function AddEditor(Request $request)
    {
        $user = Sentinel::registerAndActivate([
            'email' => $request->email,
            'password' => $request->email,
        ]);
        $user->level = '2';
        $user->name_user = $request->name_user;
        $user->alias = $request->alias;
        $user->address_user = $request->address_user;
        $user->phone = $request->phone;
        $user->active = $request->active;
        if (!empty($request->photo_user)) {
            $ext_foto = $request->photo_user->getClientOriginalExtension();
            $filename = "Editor_"."$request->email".'.'.$ext_foto;
            $temp_foto = 'AssetsAdmin/dist/img/Editor/';
            $proses = $request->photo_user->move($temp_foto, $filename);
            $user->photo_user = $filename;
        }
        
        $user->save();
        if ($user) {
            return Redirect::route('editor')->with('title', 'Success !')->with('message', 'Editor Iklan Successfully Created !!')->with('type', 'success');
        } else {
            return Redirect::route('editor')->with('title', 'Whoops!!!')->with('message', 'Editor Iklan Failed to Create !!')->with('type', 'error');
        }
    }
    public function formUpdateEditor(Request $request)
    {
        $data['user'] = Users::find($request->id);
        $content = view('Admin.pengguna.editor.formEdit', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function UpdateEditor(Request $request)
    {
        $user = Users::find($request->id);
        $user->name_user = $request->name_user;
        $user->alias = $request->alias;
        $user->address_user = $request->address_user;
        $user->phone = $request->phone;
        $user->active = $request->active;
        if (!empty($request->photo_user)) {
            $ext_foto = $request->photo_user->getClientOriginalExtension();
            $filename = "Editor_"."$request->email".'.'.$ext_foto;
            $temp_foto = 'AssetsAdmin/dist/img/Editor/';
            $proses = $request->photo_user->move($temp_foto, $filename);
            $user->photo_user = $filename;
        }
        $user->save();
        if ($user) {
            return Redirect::route('editor')->with('title', 'Success !')->with('message', 'Editor Iklan Successfully Updated !!')->with('type', 'success');
        } else {
            return Redirect::route('editor')->with('title', 'Whoops!!!')->with('message', 'Editor Iklan Failed to Update !!')->with('type', 'error');
        }
    }

    public function resetSandiEditor(Request $request)
    {
        $user = Sentinel::findById($request->id);
        $email = $user->email;
        $credentials = [
            'password' => $email,
        ];
        $user = Sentinel::update($user, $credentials);
        if ($user) {
            return ['status' => 'success', 'message' => 'Password Successfully Reseted !!'];
        } else {
            return ['status'=>'error', 'message'=>'Password Failed to Reset !!'];
        }
    }
    /* =========================================================
    ========================= IKLAN ============================
    ========================================================= */
    public function iklanAtas(Request $request)
    {
        $data['mn_active'] = 'iklan';
        $data['title'] = 'Iklan';
        $data['smallTitle'] = 'Atas';
        $data['iklan'] = Iklan::find(1);
        return view('Admin.iklan.main', $data);
    }
    public function iklanTengah(Request $request)
    {
        $data['mn_active'] = 'iklan';
        $data['title'] = 'Iklan';
        $data['smallTitle'] = 'Bawah';
        $data['iklan'] = Iklan::find(2);
        return view('Admin.iklan.main', $data);
    }
    public function iklanBawah(Request $request)
    {
        $data['mn_active'] = 'iklan';
        $data['title'] = 'Iklan';
        $data['smallTitle'] = 'Samping';
        $data['iklan'] = Iklan::find(3);
        return view('Admin.iklan.main', $data);
    }
    public function iklanSamping(Request $request)
    {
        $data['mn_active'] = 'iklan';
        $data['title'] = 'Iklan';
        $data['smallTitle'] = 'Samping';
        $data['iklan'] = Iklan::find(4);
        return view('Admin.iklan.main', $data);
    }
    public function profileGambar(Request $request)
    {
        $data['mn_active'] = 'modulWeb';
        $data['title'] = 'Profile';
        $data['smallTitle'] = '';
        $data['iklan'] = Iklan::find(5);
        return view('Admin.iklan.main', $data);
    }
    public function formIklan(Request $request)
    {
        $data['iklan'] = Iklan::find($request->id);
        $content = view('Admin.iklan.formEdit', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }
    public function UpdateIklan(Request $request)
    {
        $iklan = Iklan::find($request->id_iklan);
        $iklan->judul_iklan = $request->judul_iklan;
        $iklan->url = $request->url;
        $iklan->tgl_iklan = date('Y-m-d');
        $iklan->aktif = $request->aktif;
        $posisi = $iklan->posisi;
        if (!empty($request->gambar_iklan)) {
            $ext_foto = $request->gambar_iklan->getClientOriginalExtension();
            $filename = "Iklan_".$posisi."_".date('dmYHis').".".$ext_foto;
            $temp_foto = 'AssetsSite/img/iklan/';
            $proses = $request->gambar_iklan->move($temp_foto, $filename);
            $iklan->gambar_iklan = $filename;
        }
        $iklan->save();
        if ($posisi == "Atas") {
            $rute = 'iklanAtas';
        }elseif ($posisi == "Tengah") {
            $rute = 'iklanTengah';
        }elseif ($posisi == "Bawah") {
            $rute = 'iklanBawah';
        }elseif ($posisi == "Samping") {
            $rute = 'iklanSamping';
        }elseif ($posisi == "Profile") {
            $rute = 'profile';
        }
        if ($iklan) {
            return Redirect::route($rute)->with('title', 'Success !')->with('message', 'Iklan Successfully Updated !!')->with('type', 'success');
        } else {
            return Redirect::route($rute)->with('title', 'Whoops!!!')->with('message', 'Iklan Failed to Update !!')->with('type', 'error');
        }
    }
    /* =========================================================
    ====================== KATA JOROK ==========================
    ========================================================= */
    public function kataJorok(Request $request)
    {
        $data['mn_active'] = 'kataJorok';
        $data['title'] = 'Kata Jorok';
        $data['kataJorok'] = KataJorok::all();
        return view('Admin.kataJorok.main', $data);
    }
    public function kataJorokDatagrid(Request $request)
    {
        $data = KataJorok::getJson($request);
        return response()->json($data);
    }
    public function formAddKataJorok(Request $request)
    {
        $content = view('Admin.kataJorok.formAdd')->render();
        return ['status' => 'success', 'content' => $content];
    }
    public function AddKataJorok(Request $request)
    {
        $kata = new KataJorok;
        $kata->kata_asli = $request->kata_asli;
        $kata->diganti = $request->diganti;
        $kata->save();
        if ($kata) {
            return Redirect::route('kataJorok')->with('title', 'Success !')->with('message', 'Kata Jorok Successfully Created !!')->with('type', 'success');
        } else {
            return Redirect::route('kataJorok')->with('title', 'Whoops!!!')->with('message', 'Kata Jorok Failed to Create !!')->with('type', 'error');
        }
    }
    public function formUpdateKataJorok(Request $request)
    {
        $data['kataJorok'] = KataJorok::find($request->id);
        $content = view('Admin.kataJorok.formEdit', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }
    public function UpdateKataJorok(Request $request)
    {
        $kata = KataJorok::find($request->id_kata_jorok);
        $kata->kata_asli = $request->kata_asli;
        $kata->diganti = $request->diganti;
        $kata->save();
        if ($kata) {
            return Redirect::route('kataJorok')->with('title', 'Success !')->with('message', 'Kata Jorok Successfully Updated !!')->with('type', 'success');
        } else {
            return Redirect::route('kataJorok')->with('title', 'Whoops!!!')->with('message', 'Kata Jorok Failed to Update !!')->with('type', 'error');
        }
    }
    public function deleteKataJorok(Request $request)
    {
        $id_kata_jorok = $request->id;
        $kata = KataJorok::find($id_kata_jorok);
        if(!empty($kata)){
            $kata->delete();
            return ['status' => 'success', 'message' => 'Kata Jorok Successfully Deleted'];
        }else{
            return ['status'=>'error', 'message'=>'Invalid user.'];
        }
    }

    // PROFILE
    public function profile(){
        $data['mn_active']="";
        $data['title'] = 'Detail Profile';
        return view('Admin.profile.main',$data);
    }

    public function form_ubah_password(){
        $data['mn_active']="berita";
        $data['title'] = 'Detail Profile';
        $content = view('Admin.profile.form',$data)->render();
        return ['status'=>'success','content'=>$content];
    }

    public function ubah_password(Request $request)
    {
        $password_baru = $request->baru;
        $password_lama = $request->lama;
        $user = Sentinel::getUser();
        $hasher = Sentinel::getHasher();
        if (!$hasher->check($password_lama, $user->password)) {
            return Redirect::to('profileAdmin')->with('title', 'SALAH !')->with('message', 'Password Salah, Silahkan Coba Lagi !!')->with('type', 'error');
        } else {
            Sentinel::update($user, array('password' => $password_baru));
            return Redirect::to('profileAdmin')->with('title', 'Berhasil !')->with('message', 'Password Berhasil Di Perbaharui !!')->with('type', 'success');
        }
    }

    /* =========================================================
    ====================== MODUL BERITA ==========================
    ========================================================= */
    public function beritaSekolah(Request $request)
    {
        $data['id'] = $request->id;
        $data['mn_active'] = 'berita';
        if($request->id==1){
            $data['title'] = 'Inovasi';
        }else if($request->id==2){
            $data['title'] = 'Prestasi';
        }
        $data['identity'] = Identity::find(1);
        return view('Admin.berita.berita_sekolah.main', $data);
    }

    public function tampilBeritaSekolah(Request $request){
        $isi = $request->kategori;
        if ($isi==1) {
            $data = Berita::getBeritaSekolah($request,$isi);
        }else if($isi==2){
            $data = Berita::getEvent($request,$isi);
        }else if($isi==3){
            $data = Berita::getPengumuman($request,$isi);
        }else if($isi==4){
            $data = Berita::getprestasi($request,$isi);
        }else if($isi==5){
            $data = Berita::getprogramunggulan($request,$isi);
        }
        return response()->json($data);
    }

    public function formAddBeritaSekolah(Request $request)
    {
        $data['kategori'] = $request->kategori;
        if($request->kategori==1){
            $data['title'] = 'Inovasi';
        }else if($request->kategori==2){
            $data['title'] = 'Prestasi';
        }
        $content = view('Admin.berita.berita_sekolah.formAdd',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function formUpdateBeritaSekolah(Request $request)
    {
        $data['kategori'] = $request->kategori;
        $data['berita'] = Berita::find($request->id);
        $content = view('Admin.berita.berita_sekolah.formEdit',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function uploadBeritaSekolah(Request $request)
    {
        $berita = new Berita;
        $berita->judul = $request->judul;
        $berita->isi = $request->isi_berita;
        $berita->status = $request->status;
        $berita->kategori = $request->kategori;
        $berita->editor_id = Sentinel::getUser()->id;

        if($request->kategori==2){
            $berita->tanggal_acara = $request->tanggal_acara;
        }

        $berita->tanggal = date('Y-m-d');
        $berita->jam = date('H:i');
        $foto = date('YmdHis');
        if (!empty($request->gambar)) {
           $ukuranFile1 = filesize($request->gambar);
            if ($ukuranFile1 <= 500000) {
                $ext_foto1 = $request->gambar->getClientOriginalExtension();
                $filename1 = "img_inovasi_".date('Ymd-His').".".$ext_foto1;
                $temp_foto1 = 'uploads/berita/';
                $proses1 = $request->gambar->move($temp_foto1, $filename1);
                $berita->gambar = $filename1;
            }else{
                $file1=$_FILES['gambar']['name'];
                if(!empty($file1)){
                    $direktori1="uploads/berita/"; //tempat upload foto
                    $name1='gambar'; //name pada input type file
                    $namaBaru1="img_inovasi_".date('Ymd-His'); //name pada input type file
                    $quality1=50; //konversi kualitas gambar dalam satuan %
                    $upload1 = compressFile::UploadCompress($namaBaru1,$name1,$direktori1,$quality1);
                }
                $ext_foto1 = $request->gambar->getClientOriginalExtension();
                $berita->gambar = $namaBaru1.".".$ext_foto1;
            }
        }
        $berita->save();

        $notif = ($berita->kategori == 1) ? 'Inovasi' : 'Prestasi';
        if ($berita) {
            return Redirect('admin/berita/beritaSekolah/'.$request->kategori)->with('title', 'Success !')->with('message', $notif.' Successfully to Upload !!')->with('type', 'success');
        } else {
            return Redirect('admin/berita/beritaSekolah/'.$request->kategori)->with('title', 'Whoops!!!')->with('message', $notif.' Failed to Upload !!')->with('type', 'error');
        }
    }

    public function updateBeritaSekolah(Request $request)
    {
        $berita = Berita::find($request->id_berita);
        $berita->judul = $request->judul;
        $berita->isi = $request->isi_berita;
        $berita->status = $request->status;
        if($request->kategori==2){
            $berita->tanggal_acara = $request->tanggal_acara;
        }
        $berita->tanggal = date('Y-m-d');
        $berita->jam = date('H:i');
        $foto = date('YmdHis');
        if (!empty($request->gambar)) {
            if($berita->gambar!=''){
                if(file_exists('uploads/berita/'.$berita->gambar)){
                    unlink('uploads/berita/'.$berita->gambar);
                }
            }

            $ukuranFile1 = filesize($request->gambar);
            if ($ukuranFile1 <= 500000) {
                $ext_foto1 = $request->gambar->getClientOriginalExtension();
                $filename1 = "img_inovasi_".date('Ymd-His').".".$ext_foto1;
                $temp_foto1 = 'uploads/berita/';
                $proses1 = $request->gambar->move($temp_foto1, $filename1);
                $berita->gambar = $filename1;
            }else{
                $file1=$_FILES['gambar']['name'];
                if(!empty($file1)){
                    $direktori1="uploads/berita/"; //tempat upload foto
                    $name1='gambar'; //name pada input type file
                    $namaBaru1="img_inovasi_".date('Ymd-His'); //name pada input type file
                    $quality1=50; //konversi kualitas gambar dalam satuan %
                    $upload1 = compressFile::UploadCompress($namaBaru1,$name1,$direktori1,$quality1);
                }
                $ext_foto1 = $request->gambar->getClientOriginalExtension();
                $berita->gambar = $namaBaru1.".".$ext_foto1;
            }
        }

        $berita->save();
        $notif = ($berita->kategori == 1) ? 'Inovasi' : 'Prestasi';
        if ($berita) {
            return Redirect('admin/berita/beritaSekolah/'.$request->kategori)->with('title', 'Success !')->with('message', $notif.' Successfully to Update !!')->with('type', 'success');
        } else {
            return Redirect('admin/berita/beritaSekolah/'.$request->kategori)->with('title', 'Whoops!!!')->with('message', $notif.' Failed to Update !!')->with('type', 'error');
        }
    }

    public function deleteBeritaSekolah(Request $request)
    {
        $amtv = Berita::where('id_berita',$request->id)->delete();
        if($amtv){
            return ['status' => 'success'];
        }
    }

    /* =========================================================
    ============================ AMTV ==========================
    ========================================================= */

    public function amtv(){
        $data['mn_active'] = 'amtv';
        $data['title'] = 'Video Youtube';
        return view('Admin.media.amtv.main', $data);
    }

    public function tampilAmtv(Request $request){
        $data = Amtv::getAmtv($request);
        return response()->json($data);
    }

    public function formAddAmtv(Request $request)
    {
        $content = view('Admin.media.amtv.formAdd')->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function formUpdateAmtv(Request $request)
    {
        $data['id'] = $request->id;
        $data['amtv'] = Amtv::find($request->id);
        $content = view('Admin.media.amtv.formEdit',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function uploadAmtv(Request $request)
    {
        $amtv = new Amtv;
        $amtv->judul_amtv = $request->judul;
        $amtv->file = $request->youtube;
        $amtv->status_amtv = $request->status;
        $amtv->save();
        if($amtv){
            return Redirect::route('amtv')->with('title', 'Success !')->with('message', 'Video Successfully to Upload !!')->with('type', 'success');
        }else{
            return Redirect::route('amtv')->with('title', 'Success !')->with('message', 'Video Failed to Upload !!')->with('type', 'success');
        }
    }

    public function updateAmtv(Request $request)
    {
        $amtv = Amtv::find($request->id_amtv);
        $amtv->judul_amtv = $request->judul;
        $amtv->file = $request->youtube;
        $amtv->status_amtv = $request->status;
        $amtv->save();
        if($amtv){
            return Redirect::route('amtv')->with('title', 'Success !')->with('message', 'Video Successfully to Upload !!')->with('type', 'success');
        }else{
            return Redirect::route('amtv')->with('title', 'Success !')->with('message', 'Video Failed to Upload !!')->with('type', 'success');
        }
    }

    public function deleteAmtv(Request $request)
    {
        $amtv = Amtv::where('id_amtv',$request->id)->delete();
        if($amtv){
            return ['status' => 'success'];
        }
    }


    /* =========================================================
    ============================ Video ==========================
    ========================================================= */

    public function video(){
        $data['mn_active'] = 'amtv';
        $data['title'] = 'Video';
        return view('Admin.media.video.main', $data);
    }

    public function tampilvideo(Request $request){
        $data = Video::getvideo($request);
        return response()->json($data);
    }

    public function formAddvideo(Request $request)
    {
        $content = view('Admin.media.video.formAdd')->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function formUpdatevideo(Request $request)
    {
        $data['id'] = $request->id;
        $data['amtv'] = Video::find($request->id);
        $content = view('Admin.media.video.formEdit',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function uploadvideo(Request $request)
    {
        $video = new Video;
        $video->judul = $request->judul;
        $video->url = $request->url;
        $video->save();
        if($video){
            return Redirect::route('video')->with('title', 'Success !')->with('message', 'AMTV Successfully to Upload !!')->with('type', 'success');
        }else{
            return Redirect::route('video')->with('title', 'Success !')->with('message', 'AMTV Failed to Upload !!')->with('type', 'success');
        }
    }

    public function updatevideo(Request $request)
    {
        $video = Video::find($request->id);
        $video->judul = $request->judul;
        $video->url = $request->url;
        $video->save();
        if($video){
            return Redirect::route('video')->with('title', 'Success !')->with('message', 'AMTV Successfully to Upload !!')->with('type', 'success');
        }else{
            return Redirect::route('video')->with('title', 'Success !')->with('message', 'AMTV Failed to Upload !!')->with('type', 'success');
        }
    }

    public function deletevideo(Request $request)
    {
        $amtv = Amtv::where('id_amtv',$request->id)->delete();
        if($amtv){
            return ['status' => 'success'];
        }
    }



    /* =========================================================
    ============================ GLERI ==========================
    ========================================================= */

    public function galeri(){
        $data['mn_active'] = 'amtv';
        $data['title'] = 'Galeri';
        return view('Admin.media.galeri.main', $data);
    }

    public function tampilGaleri(Request $request){
        $data = Galeri::getGaleri($request);
        return response()->json($data);
    }

    public function formAddGaleri(Request $request)
    {
        $content = view('Admin.media.galeri.formAdd')->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function formUpdateGaleri(Request $request)
    {
        $data['id'] = $request->id;
        $data['galeri'] = Galeri::find($request->id);
        $content = view('Admin.media.galeri.formEdit',$data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function uploadGaleri(Request $request)
    {
        $galeri = new Galeri;
        $galeri->kategori_galeri = 1;
        $galeri->deskripsi_galeri = $request->deskripsi;
        $galeri->status_galeri = $request->status;

        $foto = date('YmdHis');
        if (!empty($request->file)) {
            $ukuranFile1 = filesize($request->file);
            if ($ukuranFile1 <= 500000) {
                $ext_foto1 = $request->file->getClientOriginalExtension();
                $filename1 = "img_galeri_".date('Ymd-His').".".$ext_foto1;
                $temp_foto1 = 'uploads/galeri/';
                $proses1 = $request->file->move($temp_foto1, $filename1);
                $berita->file_galeri = $filename1;
            }else{
                $file1=$_FILES['file']['name'];
                if(!empty($file1)){
                    $direktori1="uploads/galeri/"; //tempat upload foto
                    $name1='file'; //name pada input type file
                    $namaBaru1="img_galeri_".date('Ymd-His'); //name pada input type file
                    $quality1=50; //konversi kualitas gambar dalam satuan %
                    $upload1 = compressFile::UploadCompress($namaBaru1,$name1,$direktori1,$quality1);
                }
                $ext_foto1 = $request->file->getClientOriginalExtension();
                $galeri->file_galeri = $namaBaru1.".".$ext_foto1;
            }
        }

        $galeri->save();
        if($galeri){
            return Redirect::route('galeri')->with('title', 'Success !')->with('message', 'Galeri Successfully to Upload !!')->with('type', 'success');
        }else{
            return Redirect::route('galeri')->with('title', 'Success !')->with('message', 'Galeri Failed to Upload !!')->with('type', 'success');
        }
    }

    public function updateGaleri(Request $request)
    {
        $galeri = Galeri::find($request->id_galeri);
        $foto = date('YmdHis');
        if (!empty($request->file)) {
            if($galeri->file_galeri!=''){
                if(file_exists('uploads/galeri/'.$galeri->file_galeri)){
                    unlink('uploads/galeri/'.$galeri->file_galeri);
                }
            }
            $ukuranFile1 = filesize($request->file);
            if ($ukuranFile1 <= 500000) {
                $ext_foto1 = $request->file->getClientOriginalExtension();
                $filename1 = "img_galeri_".date('Ymd-His').".".$ext_foto1;
                $temp_foto1 = 'uploads/galeri/';
                $proses1 = $request->file->move($temp_foto1, $filename1);
                $galeri->file_galeri = $filename1;
            }else{
                $file1=$_FILES['file']['name'];
                if(!empty($file1)){
                    $direktori1="uploads/galeri/"; //tempat upload foto
                    $name1='file'; //name pada input type file
                    $namaBaru1="img_galeri_".date('Ymd-His'); //name pada input type file
                    $quality1=50; //konversi kualitas gambar dalam satuan %
                    $upload1 = compressFile::UploadCompress($namaBaru1,$name1,$direktori1,$quality1);
                }
                $ext_foto1 = $request->file->getClientOriginalExtension();
                $galeri->file_galeri = $namaBaru1.".".$ext_foto1;
            }
        }
        $galeri->kategori_galeri = 1;
        $galeri->deskripsi_galeri = $request->deskripsi;
        $galeri->status_galeri = $request->status;

        $galeri->save();
        if($galeri){
            return Redirect::route('galeri')->with('title', 'Success !')->with('message', 'Galeri Successfully to Update !!')->with('type', 'success');
        }else{
            return Redirect::route('galeri')->with('title', 'Success !')->with('message', 'Galeri Failed to Update !!')->with('type', 'success');
        }
    }

    public function deleteGaleri(Request $request)
    {
        $gambar = Galeri::find($request->id);
        if($gambar->kategori_galeri=='1'){
            if($gambar->file_galeri!=''){
                if(file_exists('uploads/galeri/'.$gambar->file_galeri)){
                    unlink('uploads/galeri/'.$gambar->file_galeri);
                }
            }
        }
        $galeri = Galeri::where('id_galeri',$request->id)->delete();
        if($galeri){
            return ['status' => 'success'];
        }
    }

}
