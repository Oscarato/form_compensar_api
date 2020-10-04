<?php

namespace Config;

/**
 * Rest class
 */
class Rest
{

    //--------------------------------------------------------------------

    /**
     * HTTP protocol
     * 
     * Set to force the use of HTTPS for REST API calls
     *
     * @var bool
     */
    public $forceHttps = false;

    //--------------------------------------------------------------------

    /**
     * REST Output Format
     * 
     * The default format of the response
     * 
     * 'array':      Array data structure
     * 'csv':        Comma separated file
     * 'json':       Uses json_encode(). Note: If a GET query string
     *               called 'callback' is passed, then jsonp will be returned
     * 'html'        HTML using the table library in CodeIgniter
     * 'php':        Uses var_export()
     * 'serialized':  Uses serialize()
     * 'xml':        Uses simplexml_load_string()
     *
     * @var string
     */
    public $restDefaultFormat = 'json';

    //--------------------------------------------------------------------

    /**
     * REST Supported Output Formats
     * 
     * The following setting contains a list of the supported/allowed formats.
     * You may remove those formats that you don't want to use.
     * If the default format $this->restDefaultFormat is missing within
     * $config['restSupportedFormats'], it will be added silently during
     * REST_Controller initialization.
     *
     * @var array
     */
    public $restSupportedFormats = [
        'json',
        'xml',
        // 'array',
        // 'csv',
        // 'html',
        // 'jsonp',
        // 'php',
        // 'serialized',
    ];

    //--------------------------------------------------------------------

    /**
     * REST Status Field Name
     * 
     * The field name for the status inside the response
     *
     * @var string
     */
    public $restStatusFieldName = 'response';

    //--------------------------------------------------------------------

    /**
     * REST Message Field Name
     * 
     * The field name for the message inside the response
     *
     * @var string
     */
    public $restMessageFieldName = 'message';

    //--------------------------------------------------------------------

    /**
     * REST Data Field Name
     * 
     * The field name for the data inside the response
     *
     * @var string
     */
    public $restDataFieldName = 'data';

    //--------------------------------------------------------------------

    /**
     * CORS Check
     * 
     * Set to TRUE to enable Cross-Origin Resource Sharing (CORS). Useful if you
     * are hosting your API on a different domain from the application that
     * will access it through a browser
     *
     * @var bool
     */
    public $checkCors = true;

    //--------------------------------------------------------------------

    /**
     * CORS Allowable Headers
     * 
     * If using CORS checks, set the allowable headers here
     *
     * @var array
     */
    public $allowedCorsHeaders = [
        'Origin',
        'X-Requested-With',
        'Content-Type',
        'Accept',
        'Access-Control-Request-Method',
        'Access-Control-Request-Headers',
        'Access-Control-Allow-Origin',
        'Accept-Encoding',
        'Accept-Language',
        'User-Agent',
        'X-Token-Compensar',
        'XMLHTTPRequest'
    ];

    //--------------------------------------------------------------------

    /**
     * CORS Allowable Methods
     * 
     * If using CORS checks, you can set the methods you want to be allowed
     *
     * @var array
     */
    public $allowedCorsMethods = [
        'GET',
        'POST',
        'OPTIONS',
        'PUT',
        'DELETE'
    ];

    //--------------------------------------------------------------------

    /**
     * CORS Allow Any Domain
     * 
     * Set to TRUE to enable Cross-Origin Resource Sharing (CORS) from any
     * source domain
     *
     * @var bool
     */
    public $allowAnyCorsDomain = false;

    //--------------------------------------------------------------------

    /**
     * CORS Allowable Domains
     * 
     * Used if $this->checkCors is set to TRUE and $this->allowAnyCorsDomain
     * is set to FALSE. Set all the allowable domains within the array
     * 
     * @var array
     */
    public $allowedCorsOrigins = [
        'http://localhost',
        'localhost'
    ];

    //--------------------------------------------------------------------

    /**
     * REST Message Field Name
     * 
     * The field name for the message inside the response
     *
     * @var string
     */
    public $restKeyName = 'X-Token-Compensar';


}
