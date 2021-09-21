<?php
//setting keamanan : nama db
use Slim\App;

//mau request data berdasarkan api_key
return function (App $app) {
    // e.g: $app->add(new \Slim\Csrf\Guard);
    $app->add(new \Tuupola\Middleware\JwtAuthentication([
        "path" => "/important", /* or ["/api", "/admin"]*/ 
        "secure" => false, /*kondisi false tidak digunakan*/
        "attribute" => "decoded_token_data",
        "algorithm" => ["HS256"],
        "error" => function ($response, $arguments) {
            $data["status"] = "crashed";
            $data["message"] = $arguments["message"];
            return $response
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    ]));
    /*$app->add(function ($request, $response, $next) {
    
        $key = $request->getQueryParam("key");
    
        if(!isset($key)){
            return $response->withJson(["status" => "sukses akses API"], 401);
        }
        
        $sql = "SELECT * FROM api_user WHERE api_key=:api_key";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":api_key" => $key]);
        
        if($stmt->rowCount() > 0){
            $result = $stmt->fetch();
            if($key == $result["api_key"]){
            
                // update hit
                $sql = "UPDATE api_user SET hit=hit+1 WHERE api_key=:api_key";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([":api_key" => $key]);
                
                return $response = $next($request, $response);
            }
        }
    
        return $response->withJson(["status" => "Gagal API"], 401);
    
    });*/
};
