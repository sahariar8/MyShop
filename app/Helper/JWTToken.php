<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PhpParser\Node\Stmt\Static_;

class JWTToken
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function CreateToken($userEmail, $userId)
    {
        $payload = [
            'iss' => 'Laravel-token',
            'iat' => time(),
            'exp' => time() + 60 * 60,
            'userEmail' => $userEmail,
            'userId' => $userId,  
        ];

        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    public Static function ReadToken($token)
    {
        try {
            if($token == null)
            {
                return "Unauthorised";
            }
            $decode = JWT::decode($token,new Key(env('JWT_SECRET'),'HS256'));
            return $decode;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


}
