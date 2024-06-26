<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
    }
    
    @page { margin: 0px; }
    body { margin: 0px; }
    
    #depan {
        position: absolute;
        z-index: -1;
        width: 100%;
        height:99%;
    }

    #sertifikat {
        position: absolute;
        width: 100%;
        text-align : center;
        color: #1D2F5F;
        top: 16%;
        font-size : 85px;
    }
    
    #nomor {
        position: absolute;
        width: 100%;
        top: 28%;
        font-size : 16px;
        text-align : center;
    }
    
    #title1 {
        position: absolute;
        top: 38%;
        width: 100%;
        font-size : 22px;
        text-align : center;
    }
    
    #title2 {
        position: absolute;
        width : 100%;
        top: 44%;
        color: #1D2F5F;
        font-size : 30px;
        text-align : center;
    }
    
    #title3 {
        position: absolute;
        width : 100%;
        top: 54%;
        color: #1D2F5F;
        font-size : 16px;
        text-align : center;
    }
    
    #title4 {
        position: absolute;
        top: 75%;
        left: 40%;
        color: #1D2F5F;
        font-size : 16px;
        text-align : left;
    }
    
    #title5 {
        position: absolute;
        top: 90%;
        left: 40%;
        color: #1D2F5F;
        font-size : 16px;
        text-align : left;
    }
    
    #ttd {
        position: absolute;
        top: 77%;
        left: 38%;
        color: #1D2F5F;
        width: 25%;
    }
    
    #fotoPeserta {
        position: absolute;
        top: 76%;
        left: 27%;
        color: #1D2F5F;
        width: 100px;
    }
    
    .logodepan {
        position: absolute;
        top:0;
        right: 0;
        width: 30%;
        height: auto;
        background-color: white;
    }
    
    .page-break {
        page-break-after: always;
    }
    
    #belakang {
        position: absolute;
        z-index: -1;
        width: 100%;
        height:99%; 
    }
    
    #pelatihans {
        position: absolute;
        width: 100%;
        text-align : center;
        color: #1D2F5F;
        top: 8%;
        font-size : 22px;
    }
    
    #units {
        position: absolute;
        width: 80%;
        top: 21%;
        left: 19%;
    }
    
    #units2 {
        position: absolute;
        width: 80%;
        top: 27%;
        left: 19%;
        color: black;
    }
</style>

<body>

    <div class="logodepan">
        <img src="<?= $logodepan ?>" width="100%"/>
    </div>
    <div class="page-break">
        
        <div style="z-index:1">
            <b id="sertifikat">SERTIFIKAT</b>
    
            <span id="nomor">
                Nomor : <?= str_pad($sertifikat->no_sertifikat,5,'0',STR_PAD_LEFT); ?>/<?= $sertifikat->kode_kelas ?>/S/LPK.<?= $kode ?>/<?= App\Http\Controllers\Core\NotifikasiController::romawi(date('m')) ?>/<?= date('Y') ?>
                <br>
                Nomor Izin Lembaga : 07092200866450001
            </span>
    
            <span id="title1">
                DIBERIKAN KEPADA :
            </span>
            
            <span id="title2">
                <?= $sertifikat->nama_pengguna ?>
            </span>
            
            <span id="title3">
                Atas Kelulusan Dalam Pelatihan
                <br>
                <b style="color:red; font-size:20px"><?= $sertifikat->keterangan ?></b>
                <br>
                dan dinyatakan <b style="color:red">Kompeten</b> Standar <?= $lpk ?>
            </span>
            
            <span id="title4">
                Padang, <?= App\Http\Controllers\Core\NotifikasiController::tglIndo(date('Y-m-d')) ?>
                <br>
                Pimpinan <?= $lpk ?>
            </span>
            <?php if($ttd != null) : ?>
                <img src="<?= $ttd ?>" id="ttd" alt="">
            <?php endif ?>
            
            <span id="title5">
                <?= $pimpinan ?>
            </span>
    
            <img id="fotoPeserta" src="<?= $sertifikat->foto ?? 'peserta/xamplefoto.png' ?>" alt="profile">
            <div style="position:absolute; z-index:1; margin-top:55%; margin-left:80%">
                <img width="80px" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(256)->generate($qr)) !!} ">
            </div>
            
                <p style="position:absolute; z-index:1; margin-top:64%; margin-left:77%">Verifikasi Sertifikat</p>
        </div>
        <img id="depan" src="<?= $bg_depan ?>"/>
    </div>
    
    <div>
        <img id="belakang" src="<?= $bg_belakang ?>"/>
        
        <p id="pelatihans">Pelatihan : <?= $sertifikat->keterangan ?></p>
        
        
        <table id="units">
            <tr style="color:white; font-size:14px">
                <td style="width:50px">NO</td>
                <td style="width:300px; padding-left:80px">UNIT KOMPETENSI</td>
                <td>KODE UNIT</td>
            </tr>
        </table>
        <table id="units2">
            <?php foreach($unit as $i => $a){ ?>
            
            <tr style="color:black; font-size:14px">
                <td style="width:50px"><?= $i+1 ?></td>
                <td style="width:290px"><?= $a->unit_kompetensi ?></td>
                <td style="padding-left:80px"><?= $a->kode_unit ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
    
</body>
</html>