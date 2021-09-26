<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;
use Slim\Handlers\Strategies\RequestResponse;

return function (App $app) {
    $container = $app->getContainer();

    $app->group("/important", function(App $app){ //sesuai path
        $app->get("/test", function (Request $request, Response $response){
            return $response->withJson(['status'=>'error','message'=>'coba lagi'],404);
        });
    });

    $app->get("/test", function (Request $request, Response $response){
        $time=time();
        $settings = $this->get('settings');
        $exp = $time + 21000;

        $tokendata = array(
            "iss"=>'lpaip.ukdw.ac.id',
            "sub"=>'rps',
            "aud"=>'user@gmail.com',
            "iat"=>$time, //waktu terbit
            "nbf"=>$time, //waktu bisa dipakainya
            "exp"=>$exp,
            "id_pengguna" =>'123',
            "id_role" => 'Admin',
            "id_unit" => '1',
        );
        //generate jwt
        $token= JWT::encode($tokendata, $settings['jwt']['secret'], "HS256");
        return $response->withJson(['status'=>'sukses','message'=>'Token dibuat','token'=>$token],200);
    });

    $app->group("/inputall", function(App $app){
        $app->post("/kelas", function(Request $request, Response $response){
            //$json = $request->getParsedBody();
            //return $response->withJson(["status"=>"sukses","kode"=>$json["kode]],200);
            //$input = json_decode($json, true);
            $kelas_baru = $request->getParsedBody();
            $sql = "INSERT INTO kelas (kode, nama, tanggal_mulai, tanggal_selesai, deskripsi, tahun, created_at, is_active) VALUE (:kode, :nama, :tanggal_mulai, :tanggal_selesai, :deskripsi, :tahun, :created_at, :is_active)";
            $db = $this->db->prepare($sql);

            $data = [
                ":kode"=>$kelas_baru["kode"],
                ":nama"=>$kelas_baru["nama"],
                ":tanggal_mulai"=>$kelas_baru["tanggal_mulai"],
                ":tanggal_selesai"=>$kelas_baru["tanggal_selesai"],
                ":deskripsi"=>$kelas_baru["deskripsi"],
                ":tahun"=>$kelas_baru["tahun"],
                ":created_at"=>$kelas_baru["created_at"],
                ":is_active"=>$kelas_baru["is_active"]
            ];

            if($db->execute($data))
                return $response->withJson(["status"=>"berhasil","data"=>"1"], 200);
                return $response->withJson(["status"=>"gagal","data"=>"0"], 200);
        });

        $app->post("/anggota_kelas", function (Request $request, Response $response){
            $kelas_anggota = $request->getParsedBody();
            $sql = "INSERT INTO kelas_anggota (id_kelas,id_pengguna,created_at,is_active) VALUE (:id_kelas,:id_pengguna,:created_at,:is_active)";
            $db = $this->prepare($sql);

            $data = [
                ":id_kelas"=>$kelas_anggota["id_kelas"],
                ":id_pengguna"=>$kelas_anggota["id_pengguna"],
                ":created_at"=>$kelas_anggota["created_at"],
                ":is_active"=>$kelas_anggota["is_active"]
            ];

            if($db->execute($data))
                return $response->withJson(["status"=>"berhasil","data"=>"1"],200);
                return $response->withJson(["status"=>"gagal","data"=>"0"],200);
        });

        $app->post("/pengguna", function(Request $request, Response $response){
            $user_baru = $request->getParsedBody();
            $sql = "INSERT INTO pengguna (username, password, email, nama, gender, tanggal_lahir, alamat, telepon, nik, nidn, tanda_tangan, foto, google_id, id_unit, created_at, accessed_at, is_verified, is_active) VALUE (:username, :password, :email, :nama, :gender, :tanggal_lahir, :alamat, :telepon, :nik, :nidn, :tanda_tangan, :foto, :google_id, :id_unit, :created_at, :accessed_at, :is_verified, :is_active)";
            $db = $this->db->prepare($sql);

            $data = [
                ":username"=>$user_baru["username"],
                ":password"=>$user_baru["password"],
                ":email"=>$user_baru["email"],
                ":nama"=>$user_baru["nama"],
                ":gender"=>$user_baru["gender"],
                ":tanggal_lahir"=>$user_baru["tanggal_lahir"],
                ":alamat"=>$user_baru["alamat"],
                ":telepon"=>$user_baru["telepon"],
                ":nik"=>$user_baru["nik"],
                ":nidn"=>$user_baru["nidn"],
                ":tanda_tangan"=>$user_baru["tanda_tangan"],
                ":foto"=>$user_baru["foto"],
                ":google_id"=>$user_baru["google_id"],
                ":id_unit"=>$user_baru["id_unit"],
                ":created_at"=>$user_baru["created_at"],
                ":accessed_at"=>$user_baru["accessed_at"],
                ":is_verified"=>$user_baru["is_verified"],
                ":is_active"=>$user_baru["is_active"]
            ];

            if($db->execute($data))
                return $response->withJson(["status"=>"berhasil","data"=>"1"], 200);
                return $response->withJson(["status"=>"gagal","data"=>"0"], 200);
        });

        $app->post("/pengguna_peran", function(Request $request, Response $response){
            $pengguna_peran = $request->getParsedBody();
            $sql = "INSERT INTO pengguna_peran (id_pengguna,id_peran,created_at,is_active) VALUE (:id_pengguna,:id_peran,:created_at,:is_active)";
            $db = $this->prepare($sql);

            $data = [
                ":id_pengguna"=>$pengguna_peran["id_pengguna"],
                ":id_peran"=>$pengguna_peran["id_peran"],
                ":created_at"=>$pengguna_peran["created_at"],
                ":is_active"=>$pengguna_peran["is_active"]
            ];

            if($db->execute($data))
                return $response->withJson(["status"=>"berhasil","data"=>"1"],200);
                return $response->withJson(["status"=>"gagal","data"=>"0"],200);
        });

        $app->post("/pertemuan", function (Request $request, Response $response){
            $pertemuan_baru = $request->getParsedBody();
            $sql = "INSERT INTO pertemuan(kode,nama,tanggal,deskripsi,tempat,sub_cp,materi,indikator,metode_penilaian, metode_pembelajaran,pustaka,bobot,tipe_pertemuan,id_kelas,created_at,is_active)VALUE(:kode,:nama,:tanggal,:deskripsi,:tempat,:sub_cp,:materi,:indikator,:metode_penilaian,:metode_pembelajaran,:pustaka,:bobot,:tipe_pertemuan,:id_kelas,:created_at,:is_active)";
            $db = $this->prepare($sql);

            $data = [
                ":kode"=>$pertemuan_baru["kode"],
                ":nama"=>$pertemuan_baru["nama"],
                ":tanggal"=>$pertemuan_baru["tanggal"],
                ":deskripsi"=>$pertemuan_baru["deskripsi"],
                ":tempat"=>$pertemuan_baru["tempat"],
                ":sub_cp"=>$pertemuan_baru["sub_cp"],
                ":materi"=>$pertemuan_baru["materi"],
                ":indikator"=>$pertemuan_baru["indikator"],
                ":metode_penilaian"=>$pertemuan_baru["metode_penilaian"],
                ":metode_pembelajaran"=>$pertemuan_baru["metode_pembelajaran"],
                ":pustaka"=>$pertemuan_baru["pustaka"],
                ":bobot"=>$pertemuan_baru["bobot"],
                ":tipe_pertemuan"=>$pertemuan_baru["tipe_pertemuan"],
                ":id_kelas"=>$pertemuan_baru["id_kelas"],
                ":created_at"=>$pertemuan_baru["created_at"],
                ":is_active"=>$pertemuan_baru["is_active"]
            ];

            if($db->execute($data))
                return $response->withJson(["status"=>"berhasil","data"=>"1"], 200);
                return $response->withJson(["status"=>"gagal","data"=>"0"], 200);
            
        });

        $app->post("/tugas", function (Request $request, Response $response){
            $tugas_baru = $request->getParsedBody();
            $sql = "INSERT INTO tugas (kode,judul,catatan,rubrik,lampiran,waktu_tampil,waktu_tenggat,keterlambatan,created_at,id_kelas,id_pertemuan,is_active) VALUE (:kode,:judul,:catatan,:rubrik,:lampiran,:waktu_tampil,:waktu_tenggat,:keterlambatan,:created_at,:id_kelas,:id_pertemuan,:is_active)";
            $db = $this->prepare($sql);

            $data =[
                ":kode"=>$tugas_baru["kode"],
                ":judul"=>$tugas_baru["judul"],
                ":catatan"=>$tugas_baru["catatan"],
                ":rubrik"=>$tugas_baru["rubrik"],
                ":lampiran"=>$tugas_baru["lampiran"],
                ":waktu_tampil"=>$tugas_baru["waktu_tampil"],
                ":waktu_tenggat"=>$tugas_baru["waktu_tenggat"],
                ":keterlambatan"=>$tugas_baru["keterlambatan"],
                ":created_at"=>$tugas_baru["created_at"],
                ":id_kelas"=>$tugas_baru["id_kelas"],
                ":id_pertemuan"=>$tugas_baru["id_pertemuan"],
                ":is_active"=>$tugas_baru["is_active"]
            ];

            if($db->execute($data))
                return $response->withJson(["status"=>"berhasil","data"=>"1"], 200);
                return $response->withJson(["status"=>"gagal", "data"=>"0"], 200);
        });

        $app->post("/lembar_kerja", function (Request $request, Response $response){
            $lembar_kerja = $request->getParsedBody();
            $sql = "INSERT INTO tugas_lembar_kerja (kode,catatan,umpan_balik,lampiran,terlambat,skor,submitted_at,created_at,id_pengguna,id_tugas,is_active) VALUE (:kode,:catatan,:umpan_balik,:lampiran,:terlambat,:skor,:submitted_at,:created_at,:id_pengguna,:id_tugas,:is_active)";
            $db = $this->prepare($sql);

            $data = [
                ":kode"=>$lembar_kerja["kode"],
                ":catatan"=>$lembar_kerja["catatan"],
                ":umpan_balik"=>$lembar_kerja["umpan_balik"],
                ":lampiran"=>$lembar_kerja["lampiran"],
                ":terlambat"=>$lembar_kerja["terlambat"],
                ":skor"=>$lembar_kerja["skor"],
                ":submitted_at"=>$lembar_kerja["submitted_at"],
                ":created_at"=>$lembar_kerja["created_at"],
                ":id_pengguna"=>$lembar_kerja["id_pengguna"],
                ":id_tugas"=>$lembar_kerja["id_tugas"],
                ":is_active"=>$lembar_kerja["is_active"]
            ];

            if($db->execute($data))
                return $response->withJson(["status"=>"berhasil","data"=>"1"],200);
                return $response->withJson(["status"=>"gagal","data"=>"0"],200);
        });

        $app->post("/jurnal", function (Request $request, Response $response){
            $pertemuan_jurnal = $request->getParsedBody();
            $sql = "INSERT INTO pertemuan_jurnal (kode,tanggal,tempat,kegiatan,uraian,catatan,tipe,created_at,peserta,pengajar,id_pertemuan,is_active) VALUE (:kode,:tanggal,:tempat,:kegiatan,:uraian,:catatan,:tipe,:created_at,:peserta,:pengajar,:id_pertemuan,:is_active)";
            $db = $this->prepare($sql);

            $data = [
                ":kode"=>$pertemuan_jurnal["kode"],
                ":tanggal"=>$pertemuan_jurnal["tanggal"],
                ":tempat"=>$pertemuan_jurnal["tempat"],
                ":kegiatan"=>$pertemuan_jurnal["kegiatan"],
                ":uraian"=>$pertemuan_jurnal["uraian"],
                ":catatan"=>$pertemuan_jurnal["catatan"],
                ":tipe"=>$pertemuan_jurnal["tipe"],
                ":created_at"=>$pertemuan_jurnal["created_at"],
                ":peserta"=>$pertemuan_jurnal["peserta"],
                ":pengajar"=>$pertemuan_jurnal["pengajar"],
                ":id_pertemuan"=>$pertemuan_jurnal["id_pertemuan"],
                ":is_active"=>$pertemuan_jurnal["is_active"]
            ];

            if($db->execute($data))
                return $response->withJson(["status"=>"berhasil","data"=>"1"],200);
                return $response->withJson(["status"=>"gagal","data"=>"0"],200);
        });

        $app->post("/komentar", function (Request $request, Response $response){
            $komentar = $request->getParsedBody();
            $sql = "INSERT INTO postingan_komentar (pesan,created_at,id_pengguna,id_postingan) VALUE (:pesan,:created_at,:id_pengguna,:id_postingan)";
            $db = $this->prepare($sql);

            $data = [
                ":pesan"=>$komentar["judul"],
                ":created_at"=>$komentar["created_at"],
                ":id_pengguna"=>$komentar["id_pengguna"],
                ":id_postingan"=>$komentar["id_kelas"]
            ];

            if($db->execute($data))
            return $response->withJson(["status"=>"berhasil","data"=>"1"],200);
            return $response->withJson(["status"=>"gagal","data"=>"0"],200);
        });

        $app->post("/postingan", function (Request $request, Response $response){
            $postingan = $request->getParsedBody();
            $sql = "INSERT INTO postingan (kode,judul,pesan,tipe_postingan,id_pengguna,id_kelas,id_pertemuan,created_at,is_active) VALUE (:kode,:judul,:pesan,:tipe_postingan,:id_pengguna,:id_kelas,:id_pertemuan,:created_at,:is_active)";
            $sql .= "INSERT INTO postingan_komentar (pesan, created_at, id_pengguna, id_postingan) VALUE (:pesan,:created_at,:id_pengguna,:id_postingan)";
            $db = $this->prepare($sql);

            $data = [
                ":kode"=>$postingan["kode"],
                ":judul"=>$postingan["judul"],
                ":pesan"=>$postingan["pesan"],
                ":tipe_postingan"=>$postingan["tipe_postingan"],
                ":id_pengguna"=>$postingan["id_pengguna"],
                ":id_kelas"=>$postingan["id_kelas"],
                ":id_pertemuan"=>$postingan["id_pertemuan"],
                ":created_at"=>$postingan["creates_at"],
                ":is_active"=>$postingan["is_active"]
            ];
            /*$data = [
                ":pesan"=>$postingan["pesan"],
                ":created_at"=>$postingan["created_at"],
                ":id_pengguna"=>$postingan["id_pengguna"],
                ":id_postingan"=>$postingan["id_postingan"]
            ];*/

            if($db->execute($data))
                return $response->withJson(["status"=>"berhasil","data"=>"1"],200);
                return $response->withJson(["status"=>"gagal","data"=>"0"],200);
        });

        $app->post("/lampiran", function (Request $request, Response $response){
            $lampiran = $request->getParsedBody();
            $sql = "INSERT INTO postingan_lampiran (id_kelas_berkas,id_postingan,is_active) VALUE (:id_kelas_berkas,:id_postingan,:is_active)";
            $db = $this->prepare($sql);

            $data = [
                ":id_kelas_berkas"=>$lampiran["id_kelas_berkas"],
                ":id_postingan"=>$lampiran["id_postingan"], 
                ":is_active"=>$lampiran["is_active"]
            ];

            if($db->execute($data))
                return $response->withJson(["status"=>"berhasil","data"=>"1"],200);
                return $response->withJson(["status"=>"gagal","data"=>"0"], 200);

        });
    });

    $app->group("/viewall", function (App $app){
        /*$app->get("/role", function (Request $request, Response $response){
            $sql = "SELECT *FROM peran";
            $data = $this->db->prepare($sql);
            $data->execute();
            $result = $data->fetchall();
            return $response->withJson(["status"=>"sukses","data"=>$result],200);
        });*/

        $app->get("/kelas_anggota/{id_kelas}", function(Request $request, Response $response, $args){
            $id = $args["id_kelas"];
            $sql = "SELECT * FROM kelas_anggota WHERE id_pengguna=:id_kelas";
            $data = $this->db->prepare($sql);
            $data->execute([":id_kelas" => $id]);
            $result = $data->fetchall();
            return $response->withJson(["status"=>"sukses","data"=>$result],200);
            return $response->withJson(["status"=>"gagal","data"=>"0"],200);
        });

        $app->get("/pertemuan_anggota/{id_pertemuan}", function(Request $request, Response $response, $args){
            $id = $args["id_pertemuan"];
            $sql = "SELECT *FROM pertemuan_anggota WHERE id_pengguna=:id_pertemuan";
            $data = $this->db->prepare($sql);
            $data->execute([":id_pertemuan"=>$id]);
            $result = $data->fetchall();
            return $response->withJson(["status"=>"sukses","data"=>$result],200);
            return $response->withJson(["status"=>"gagal","data"=>"0"],200);
        });

        $app->get("/anggota/{id_pertemuan}", function(Request $request, Response $response, $args){
            $id_pertemuan = $args["id_pertemuan"];
            $sql = "SELECT *FROM pertemuan_anggota WHERE id_pertemuan:=id_pertemuan";
            $data = $this->db->prepare($sql);
            $data->execute();
            $result = $data->fetch();
            return $response->withJson(["status"=>"sukses","data"=>$result],200);
        });

        
        
        
    });

    /*$app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    $app->put("/peserta/{id_peserta}", function(Request $request, Response $response){
        $id = $args["id_peserta"];
        $peserta_baru = $request->getParsedBody();
        $sql = "UPDATE peserta SET id_peserta=:id_peserta, jk=:jk WHERE id_peserta=:id_peserta";
        $db = $this->db->prepare($sql);

        $data = [
            ":nama"=>$peserta_baru["nama"],
            ":jk"=>$peserta_baru["jk"]
        ];

        if($db->execute($data))
            return $response->withJson(["status"=>"berhasil","data"=>"1"], 200);
            return $response->withJson(["status"=>"gagal","data"=>"0"], 200);
    });

    $app->delete("/peserta/{id_peserta}", function (Request $request, Response $response, $args){
        $id = $args["id_peserta"];
        $sql = "DELETE FROM peserta WHERE id_peserta=:id_peserta";
        $db = $this->db->prepare($sql);

        $data = [
            ":nama"=>$peserta_baru["nama"],
            ":jk"=>$peserta_baru["jk"]
        ];

        if($db->execute($data))
            return $response->withJson(["status"=>"berhasil","data"=>"1"], 200);
            return $response->withJson(["status"=>"gagal","data"=>"0"], 200);
    });

    $app->get("/pengguna/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "SELECT * FROM pengguna WHERE id_pengguna=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });*/

};
