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

        $app->post("/unit", function(Request $request, Response $response){
            //$json = $request->getParsedBody();
            //$input = json_decode($json, true);
            $unit_baru = $request->getParsedBody();
            $sql = "INSERT INTO unit (nama, alamat, telepon, email, induk_unit) VALUE (:nama, :alamat, :telepon, :email, :induk_unit)";
            $db = $this->db->prepare($sql);

            $data = [
                ":nama"=>$unit_baru["nama"],
                ":alamat"=>$unit_baru["alamat"],
                ":telepon"=>$unit_baru["telepon"],
                ":email"=>$unit_baru["email"],
                ":induk_unit"=>$unit_baru["induk_unit"]
            ];

            if($db->execute($data))
                return $response->withJson(["status"=>"berhasil","data"=>"1"], 200);
                return $response->withJson(["status"=>"gagal","data"=>"0"], 200);
        });

        $app->post("/pengguna", function(Request $request, Response $response){
            $user_baru = $request->getParsedBody();
            $sql = "INSERT INTO pengguna (username, password, email, nama, gender, tanggal_lahir, alamat, telepon, nik, nidn, tanda_tangan, foto, google_id, id_unit, created_at, accessed_at, is_verified, is_active) VALUE (:username, :password, :email, :nama, :gender, :tanggal_lahir, :alamat, :telepon, :nik, :nidn, :tanda_tangan, :foto, :google_id, :id_unit, :created_at, :accessed_at, :is_verified, :is_active)";
            $db = $this->db->prepare($sql);

            $data = [
                ":username"=>$user_baru["nama"],
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

        $app->post("/pendidikan", function (Request $request, Response $response){
            $list = $request->getParsedBody();
            $sql = "INSERT INTO pendidikan_riwayat(nama_jenjang, nama_instansi, jurusan, tahun_lulus,keterangan,id_pengguna) VALUE (:nama_jenjang,:nama_instansi,:jurusan,:tahun_lulus,:keterangan,:id_pengguna)";
            $db = $this->db->prepare($sql);

            $data = [
                ":nama_jenjang"=>$list["nama_jenjang"],
                ":nama_instansi"=>$list["nama_instansi"],
                ":jurusan"=>$list["jurusan"],
                ":tahun_lulus"=>$list["tahun_lulus"],
                ":keterangan"=>$list["keterangan"],
                ":id_pengguna"=>$list["id_pengguna"]
            ];

            if($db->execute($data))
                return $response->withJson(["status"=>"berhasil","data"=>"1"], 200);
                return $response->withJson(["status"=>"gagal","data"=>"0"], 200);
            
            /*$json = $request->getParsedBody();
            if($json){
                return $response->withJson(["status"=>"berhasil","data"=>"1"], 200);
                return $response->withJson(["status"=>"gagal","data"=>"0"], 200);
            }*/
        });

        $app->post("/pertemuan", function (Request $request, Response $response){
            $pertemuan_baru = $request->getParsedBody();
            $sql = "INSERT INTO pertemuan(kode,nama,tanggal,deskripsi,tautan_tempat,sub_cp,materi,indikator,metode_penilaian, metode_pembelajaran, pustaka, bobot,pengajar,tipe,id_kelas,created_at,is_active)VALUE(:kode,:nama,:tanggal,:deskripsi,:tautan_tempat,:sub_cp,:materi,:indikator,:metode_penilaian,:metode_pembelajaran,:pustaka,:bobot,:pengajar,:tipe,:id_kelas,:created_at,:is_active)";
            $db = $this->prepare($sql);

            $data = [
                ":kode"=>$pertemuan_baru["kode"],
                ":nama"=>$pertemuan_baru["nama"],
                ":tanggal"=>$pertemuan_baru["tanggal"],
                ":deskripsi"=>$pertemuan_baru["deskripsi"],
                ":tautan_tempat"=>$pertemuan_baru["tautan_tempat"],
                ":sub_cp"=>$pertemuan_baru["sub_cp"],
                ":materi"=>$pertemuan_baru["materi"],
                ":indikator"=>$pertemuan_baru["indikator"],
                ":metode_penilaian"=>$pertemuan_baru["metode_penilaian"],
                ":metode_pembelajaran"=>$pertemuan_baru["metode_pembelajaran"],
                ":pustaka"=>$pertemuan_baru["pustaka"],
                ":bobot"=>$pertemuan_baru["bobot"],
                ":pengajar"=>$pertemuan_baru["pengajar"],
                ":id_kelas"=>$pertemuan_baru["id_kelas"],
                ":created_at"=>$pertemuan_baru["created_at"],
                ":is_active"=>$pertemuan_baru["is_active"]
            ];

            if($db->execute($data))
                return $response->withJson(["status"=>"berhasil","data"=>"1"], 200);
                return $response->withJson(["status"=>"gagal","data"=>"0"], 200);
            
        });
    });

    $app->group("/viewall", function (App $app){
        $app->get("/role", function (Request $request, Response $response){
            $sql = "SELECT *FROM peran";
            $data = $this->db->prepare($sql);
            $data->execute();
            $result = $data->fetchall();
            return $response->withJson(["status"=>"sukses","data"=>$result],200);
        });

        $app->get("kelas", function(Request $request, Response $response){
            $sql = "SELECT * FROM kelas";
            $data = $this->db->prepare($sql);
            $data->execute();
            $result = $data->fetchall();
            return $response->withJson(["status"=>"sukses","data"=>$result],200);
        });

        $app->get("/pertemuan", function(Request $request, Response $response){
            $sql = "SELECT *FROM pertemuan";
            $data = $this->db->prepare($sql);
            $data->execute();
            $result = $data->fetchall();
            return $response->withJson(["status"=>"sukses","data"=>$result],200);
        });


    });

    /*$app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    $app->get("/pengguna", function (Request $request, Response $response){
        $sql = "SELECT * FROM pengguna";
        $data = $this->db->prepare($sql);
        $data->execute();
        $result = $data->fetchall();
        return $response->withJson(["status"=>"berhasil","data"=>$result], 200);
    });

    $app->get("/pengguna/{id_pengguna}", function(Request $request, Response $response){
        return $response->withJson(["status"=>"berhasil","data"=>$request], 200);
    });

    $app->post("/pengguna/q", function(Request $request, Response $response){
        $user_baru = $request->getParsedBody();
        $sql = "INSERT INTO pengguna (nama, email, password, role) VALUE (:nama, :email, :password, :role)";
        $db = $this->db->prepare($sql);

        $data = [
            ":nama"=>$user_baru["nama"],
            ":email"=>$user_baru["email"],
            ":password"=>$user_baru["password"],
            ":role"=>$user_baru["role"]
        ];

        if($db->execute($data))
            return $response->withJson(["status"=>"berhasil","data"=>"1"], 200);
            return $response->withJson(["status"=>"gagal","data"=>"0"], 200);
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
