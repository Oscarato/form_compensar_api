<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use Config\Rest;

/**
 * Jwt class
 */
class Jwt extends BaseConfig
{

    /**
     * @var string
     */
    public $nameToken = 'X-Token-Compensar';

    /**
     * @var string
     */
    public $issuer = 'jwt.issuer';

    /**
     * @var string
     */
    public $audience = 'jwt.audience';

    /**
     * @var string
     */
    public $jwt = 'jJIjlXLPfXSOuEq9IxXCxlM';

    /**
     * @var string
     */
    public $jti = 'X-Token-Compensar';

    /**
     * @var string
     */
    public $expiration = 0;


    /**
     * __construct function
     */    
    public function __construct()
    {
        $restConfig = new Rest();
        $this->jti = $this->nameToken = $restConfig->restKeyName;
    }
}
