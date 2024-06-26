<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MemberRequest;
use App\Http\Requests\KelasRequest;
use App\Http\Requests\Sertifikatrequest;
use App\Http\Requests\Fotorequest;
use App\Http\Requests\Cvrequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Penggunam;
use App\Models\Kelasm;
use App\Models\Absen;
use App\Models\Daftarkelasm;
use App\Http\Controllers\Core\UploadController as Uploadfile;

class MemberController extends Controller
{
    public function index(Request $r){
        $data['member'] = Penggunam::where('nama_pengguna','LIKE','%'.$r->cari.'%')->orderBy('id','DESC')->paginate($r->perpage ?? 10);
        return Inertia::render('Homepage/Member/Member',$data);
    }

    public function add(){
        return inertia::render('Homepage/Member/Create');
    }

    public function simpan(MemberRequest $r){
        if($r->validated()){
            Penggunam::create([
                'nama_pengguna' => $r->nama_pengguna,
                'no_telpon' => $r->no_telpon,
                'alamat' => $r->alamat,
                'email' => $r->email,
                'lokasi' => $r->lokasi,
                'password' => Hash::make('mediatama123'),
                'tgl_daftar' => date('Y-m-d'),
                'foto' => 'image/user.png',
                'info' => $r->info,
            ]);
        }

        return Redirect::route('member');
    }

    public function hapusDataMember($id){
        $hapus = Penggunam::where('id',$id)->delete();
        if($hapus){
            Daftarkelasm::where('id_user',$id)->delete();
        }
    }

    public function aktifasiakun($id){
        $cek = Penggunam::where('id',$id)->first();
        if($cek->status_akun == 'aktif'){
            $status = 'tidak aktif';
        }else{
            $status = 'aktif';
        }

        Penggunam::where('id',$id)->update(['status_akun' => $status]);

        return Redirect::route('member');
    }

    public function daftarkelas($id){
        $data['kelas'] = Kelasm::get();
        $data['member'] = Penggunam::where('id',$id)->first();
        $kelasdaftar = Daftarkelasm::join('kelas','kelas.id','daftarkelas.id_kelas')
                                            ->where('daftarkelas.id_user',$id)
                                            ->select('kelas.materi','daftarkelas.id','kelas.id as id_kelas','daftarkelas.sertifikat')
                                            ->get();
        $list = [];
        foreach($kelasdaftar as $i => $a){
            $pertemuan = DB::table('booking')
                        ->where('booking.id_daftarkelas',$a->id_kelas)
                        ->where('booking.id_user',$id)
                        ->where('status','diterima')
                        ->select(DB::raw('COUNT(*) as total'))
                        ->first();

            $total = $pertemuan ? $pertemuan->total : 0;
            $list[] = array(
                'kelas' => $a->materi,
                'total' => $total,
                'id_kelas' => $a->id_kelas,
                'id' => $a->id,
                'sertifikat' => $a->sertifikat,
            );
        }
        $data['kelasdaftar'] = $list;

        return Inertia::render('Homepage/Member/Datakelas',$data);
    }

    public function daftarkelasdetail($id, $id_kelas){
       
        $data = DB::table('booking')
                    ->join('kelas','kelas.id','booking.id_daftarkelas')
                    ->where('booking.id_daftarkelas',$id_kelas)
                    ->where('booking.id_user',$id)
                    ->get();

        return $data;
    }

    public function absen($id, $id_kelas){
        $data = Absen::join('kelas','kelas.id','absen.id_kelas')
                ->where('absen.id_user',$id)    
                ->where('absen.id_kelas',$id_kelas)
                ->select('kelas.materi','absen.jam','absen.tanggal')
                ->get();
        return $data;
    }

    public function memberabsen($id, $id_kelas){
       
       $data = Absen::create([
        'jam' => date('H:i:s'),
        'tanggal' => date('Y-m-d'),
        'id_user' => $id,
        'id_kelas' => $id_kelas,
       ]);

       return $data;
    }

    public function uploadCv(Cvrequest $r){
        if($r->validated()){
            $foto = $r->file('cv');
            if($foto){
                $filename = Uploadfile::uploadSingle($foto, 'cv/');
                $data['cv'] = $filename;
                $hasil = Penggunam::where('id',$r->id)->update($data);
            }
        }
        return Redirect::back();
    }
    
    public function uploadProfile(Fotorequest $r){
        if($r->validated()){
            $foto = $r->file('foto');
            if($foto){
                $filename = Uploadfile::uploadSingle($foto, 'profile/');
                $data['foto'] = $filename;
                $hasil = Penggunam::where('id',$r->id)->update($data);
            }
        }
        return Redirect::back();
    }
    
    public function uploadLinked(Request $r){
        
        $data['linkedin'] = $r->linkedin;
        $hasil = Penggunam::where('id',$r->id)->update($data);

        return Redirect::back();
    }

    public function kelasdaftar(KelasRequest $r){
        if($r->validated()){
            $cek = Daftarkelasm::where('id_user',$r->id_user)->where('id_kelas',$r->id_kelas)->first();
            if($cek){
                return Redirect::back();
            }
            Daftarkelasm::create([
                'id_user' => $r->id_user,
                'id_kelas' => $r->id_kelas,
            ]);
        }

        return Redirect::back();
    }

    public function hapuskelasdaftar($id){
        Daftarkelasm::where('id',$id)->delete();

        return Redirect::back();
    }

    public function generatesertifikat($id,$lokasi){
        $total = Daftarkelasm::where('no_sertifikat','!=', null)->count();
        Daftarkelasm::where('id',$id)->update([
            'sertifikat' => '1',
            'tanggal_terbit' => date('Y-m-d'),
            'no_sertifikat' => $total+1,
            'lokasi' => $lokasi
        ]);

        return Redirect::back();
    }

    public function edit($id){
        $data['member'] = Penggunam::where('id',$id)->first();
        $data['ids'] = $id;
        return Inertia::render('Homepage/Member/Edit',$data);
    }

    public function updatemember(MemberRequest $r, $id){
        if($r->validated()){
            Penggunam::where('id',$id)->update([
                'nama_pengguna' => $r->nama_pengguna,
                'no_telpon' => $r->no_telpon,
                'alamat' => $r->alamat,
                'email' => $r->email,
                'lokasi' => $r->lokasi,
                'info' => $r->info
            ]);
        
            return Redirect::route('member');
            
        }

    }

    public function checkdatauser($id){
        
        $datakelas = Daftarkelasm::where('id',$id)->first();
        
        $user = Penggunam::where('id',$datakelas->id_user)->select('cv','linkedin')->first();

        return response()->json(['data' => $user]);
    }
    
    public function checklinkedinadncv($id){
        
        $user = Penggunam::where('id',$id)->select('cv','linkedin')->first();

        return response()->json(['data' => $user]);
    }

    public function detailMember($id){
        $data['member'] = Penggunam::where('id',$id)->first();
        $data['ongoing'] = Daftarkelasm::join('kelas','kelas.id','daftarkelas.id_kelas')->where('daftarkelas.id_user',$id)->where('sertifikat','0')->get();
        $data['finish'] = Daftarkelasm::join('kelas','kelas.id','daftarkelas.id_kelas')->where('daftarkelas.id_user',$id)->where('sertifikat','1')->get();
        return Inertia::render('Homepage/Member/Detailmember',$data);
    }
}
