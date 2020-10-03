<?php

namespace Libraries;

//use CodeIgniter\HTTP\RequestInterface;
//use CodeIgniter\Security\Exceptions\SecurityException;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Config\Jwt as JwtConfig;

/**
 * JWT Class.
 * 
 * @author Oscar Jimenez <oscarato1993@gmail.com>
 */
class Jwt
{

    /**
     * Set to .your-domain.com for site-wide cookies
     *
     * @var Builder
     */
    protected $tokenBuilder;

    /**
     * Set to .your-domain.com for site-wide cookies
     *
     * @var Token
     */
    protected $token;

    /**
     * Set to .your-domain.com for site-wide cookies
     *
     * @var Token
     */
    protected $tokenParsed;

    /**
     * Set to .your-domain.com for site-wide cookies
     *
     * @var array
     */
    protected $config;

    //--------------------------------------------------------------------

    /**
     * Jwt constructor.
     *
     */
    public function __construct()
    {
        $config = new JwtConfig();
        $this->config = get_object_vars($config);
    }

    //--------------------------------------------------------------------

    /**
     * BuildToken function
     *
     * @return void
     */
    public function buildToken()
    {
        $time = time();
        $this->token = (new Builder())->issuedBy($this->config['issuer']) // Configures the issuer (iss claim)
            ->permittedFor($this->config['audience']) // Configures the audience (aud claim)
            ->identifiedBy($this->config['jti']) // Configures the id (jti claim), replicating as a header item
            ->issuedAt($time); // Configures the time that the token was issue (iat claim)
        //->canOnlyBeUsedAfter($time + 60) // Configures the time that the token can be used (nbf claim)

        //self::setExpiresAt($time);

        if (!is_null($time) && $this->config['expiration'] > 0) {
            $this->token->expiresAt($time + $this->config['expiration']); // Configures the expiration time of the token (exp claim)
        }

        //->getToken(); // Retrieves the generated token

        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * SetExpiresAt function
     *
     * @param Int $time
     * @return void
     */
    private function setExpiresAt(Int $time = null)
    {
        if (!is_null($time) && $this->config['expiration'] > 0) {
            $this->token->expiresAt($time + $this->config['expiration']); // Configures the expiration time of the token (exp claim)
        }
        //return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Set Claim function
     *
     * @param array $claim
     * @return void
     */
    public function setClaim(array $claim = null)
    {
        foreach ($claim ?? [] as $key => $claim_) {
            $this->token->withClaim($key, $claim_); // Configures a new claim, called "uid"
        }
        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Get Token function
     *
     * @param boolean $string
     * @return void
     */
    public function getToken(bool $string = false)
    {
        return ($string) ? (string) $this->token->getToken() : $this->token->getToken();
    }

    //--------------------------------------------------------------------

    /**
     * Parse Token function
     *
     * @param string $token
     * @return void
     */
    public static function parseToken(string $token = null)
    {
        return (new Parser())->parse((string) $token); // Parses from a string
    }

    //--------------------------------------------------------------------

    /**
     * Valid Token function
     *
     * @param string $token
     * @return void
     */
    private function validToken(string $token = null)
    {
        //$this->tokenParsed = (new Parser())->parse((string) $token); // Parses from a string
        return false;
    }

    //--------------------------------------------------------------------

    /**
     * Validate Session function
     *
     * @return array
     */
    public function validateSession(): array
    {
        $arrayReturn = ['status' => false, 'msg' => 'Token Requerido.'];
        //obtenemos el token
        $request = \Config\Services::request();
        $tokenSesion = $request->getHeader($this->config['nameToken']);

        if (!is_null($tokenSesion)) {

            //validamos el token
            $tokeParsed = self::parseToken($tokenSesion->getValue());
            $id = $tokeParsed->getClaim('id');
            
            //consultamos el usuario en la DB
            $userModel = new \App\Models\UsersModel();
            $user = $userModel->getData(['id' => $id], 'id, name, email, status');
            
            if (!empty($user)) {
                $user['id'] = $tokeParsed->getClaim('id');
                $user = (array) $user;
                $arrayReturn = ['status' => true, 'msg' => 'Token Valido.', 'data' => $user];
            } else {
                $arrayReturn = ['status' => false, 'msg' => 'Token Invalido.'];
            }
        }
        return $arrayReturn;
    }
}
