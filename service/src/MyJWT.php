<?php

include_once SERVICE_VENDOR . '/php-jwt-master/src/JWT.php';

use \Firebase\JWT\JWT;

//use \Firebase\JWT\UnexpectedValueException; //Provided JWT was invalid
//use \Firebase\JWT\SignatureInvalidException; // Provided JWT was invalid because the signature verification failed
//use \Firebase\JWT\BeforeValidException; //  Provided JWT is trying to be used before it's eligible as defined by 'nbf'
//use \Firebase\JWT\BeforeValidException; // Provided JWT is trying to be used before it's been created as defined by 'iat'
//use \Firebase\JWT\ExpiredException; // Provided JWT has since expired, as defined by the 'exp' claim

class MyJWT {

    private $key;
    private $alg;

    public function __construct() {
        $this->alg = array('HS256');
        
        $this->key = <<<EOD
//use \Firebase\JWT\UnexpectedValueException; //Provided JWT was invalid
//use \Firebase\JWT\SignatureInvalidException; // Provided JWT was invalid because the signature verification failed
//use \Firebase\JWT\BeforeValidException; //  Provided JWT is trying to be used before it's eligible as defined by 'nbf'
//use \Firebase\JWT\BeforeValidException; // Provided JWT is trying to be used before it's been created as defined by 'iat'
//use \Firebase\JWT\ExpiredException; // Provided JWT has since expired, as defined by the 'exp' claim
EOD;
    }

    /**
     * IMPORTANT:
     * You must specify supported algorithms for your application. See
     * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
     * for a list of spec-compliant algorithms.
     */
    public function createTokenFromLogin($user_id, $role) {

        $time_now = time();
        $payload = array(
            "iss" => "http://example.org"
            , "user" => $user_id
            , "role" => $role
            , "iat" => $time_now //issued atD
            , "nbf" => $time_now // access start
//,"exp" => "" //access expired
        );

//the header 
        $jwt = JWT::encode($payload, $this->key);
        return $jwt;
    }

    /**
     * You can add a leeway to account for when there is a clock skew times between
     * the signing and verifying servers. It is recommended that this leeway should
     * not be bigger than a few minutes.
     *
     * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
     */
// $leeway in seconds
    public function parseToken($jwt) {
        try {
            JWT::$leeway = 60;
            $decoded = JWT::decode($jwt, $this->key, $this->alg);
            return (array) $decoded;
        } catch (Exception $err) {
            throw $err;
        }
    }

}
