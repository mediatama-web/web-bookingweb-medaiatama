<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;
use App\Models\{
    Penggunam,
    Daftarkelasm,
    Kelasm,
    UnitKompetensi
};

class SertifikatController extends Controller
{
    public function index($id,$kelas){
        $data['sertifikat'] = Daftarkelasm::leftJoin('pengguna as a','a.id','daftarkelas.id_user')
                                ->leftJoin('kelas as b','b.id','daftarkelas.id_kelas')
                                ->select('a.nama_pengguna','a.foto','daftarkelas.lokasi','daftarkelas.tanggal_terbit','daftarkelas.no_sertifikat','b.kode_kelas','b.keterangan')
                                ->where('daftarkelas.id_user',$id)
                                ->where('daftarkelas.id_kelas',$kelas)
                                ->first();

        if($data['sertifikat']->lokasi == 'Nazea'){
            $data['pimpinan'] = 'Aulia Rahmi, M.kom';
            $data['lpk'] = 'LPK. NAZEA TEKNOLOGI';
            $data['bg_depan'] = "sertifikat/nazea_depan.png";
            $data['logodepan'] = "sertifikat/logodepan_nazea.png";
            $data['bg_belakang'] = "sertifikat/nazea_belakang.png";
        }else{
            $data['pimpinan'] = 'Ferri Achmad Effindri, M.kom';
            $data['lpk'] = 'LPK. MEDIATAMA WEB INDONESIA';
            $data['bg_depan'] = "sertifikat/sertifikat_depan.png";
            $data['logodepan'] = "sertifikat/logodepan_mediatama.png";
            $data['bg_belakang'] = "sertifikat/sertifikat_belakang.png";
        }

        $data['unit'] = UnitKompetensi::where('id_kelas',$kelas)->get();

        $data['ttd'] = false;

        $data['qr'] = 'http://localhost:8000/sertifikat-verif/'.$id.'/'.$kelas;

        $pdf = Pdf::loadView('sertifikat/sertifikat_depan',$data)->setPaper('a4', 'landscape');
        return $pdf->stream('sertifikat_depan.pdf');
    }

    public function verifikasiSertifikat($id, $kelas){
        $data['member'] = Penggunam::where('id',$id)->first();
        $data['kelas'] = Kelasm::where('id',$kelas)->first();
        return Inertia::render("Homepage/Sertifikat/Sertifikat",$data);
    }
}
