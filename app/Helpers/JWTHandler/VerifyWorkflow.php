<?php
namespace Helpers\JWTHandler;

use Helpers\JWTHandler\JwtHandler;

/**
 * Class VerifyWorkflow
 * @package Helpers\JWTHandler
 * Class intended to centralize the token verification workflow. Usefull when we need to login user
 */
class VerifyWorkflow
{

    /**
     * VerifyWorkflow constructor.
     */
    public function __construct()
    {
    }

    public static function verify($token): int{ //0 invalid token, 1 revoked token, 2 good token
        $jwtHandler = new JwtHandler();
        $isValidToken = $jwtHandler->validateToken($token);
        if($isValidToken){
            $isRevokedToken = $jwtHandler->isRevoked($token);
            if($isRevokedToken){
                return 1;
            }else{
                return 2;
            }
        }else{
            return 0;
        }
    }
}
