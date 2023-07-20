<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Kelasm;
use App\Http\Requests\Paketrequest;

class KelasController extends Controller
{
    public function index(Request $r){
        $data['kelas'] = Kelasm::search($r->cari)->paginate($r->perpage ?? 10);
        return Inertia::render('Homepage/Kelas/Kelas',$data);
    }

    public function tambah($id = null){
        if($id != null){
            $data['kelas'] = Kelasm::where('id',$id)->first();
        }else{
            $data['kelas'] = null;
        }
        return Inertia::render('Homepage/Kelas/Createkelas',$data);
    }

    public function save(Paketrequest $r,$id = null){
        
        if($r->validated()){
            if($id){
                Kelasm::where('id',$id)->update($r->validated());
            }else{
                Kelasm::create($r->validated());
            }
        }

        return Redirect::route('kelas');
    }

    public function hapusKelas($id){
        Kelasm::where('id',$id)->delete();
        return Redirect::back();
    }
}
