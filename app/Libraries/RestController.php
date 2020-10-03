<?php

namespace Libraries;

// use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\RESTful\ResourceController;
use Config\Rest;
use CodeIgniter\API\ResponseTrait;


/**
 * Rest Class.
 * 
 * @author Oscar Jimenez <oscarato1993@gmail.com>
 */
class RestController extends ResourceController
{
    use ResponseTrait;

    /**
     * Config Rest
     *
     * @var Rest
     */
    protected $restConfig;

    protected $origin = '';
    protected $allowedHeaders = '';
    protected $allowedMethods = '';

    /**
     * __construct function
     */
    public function __construct()
    {
        $this->restConfig = new Rest();

        // Convert the config items into strings
        $this->allowedHeaders = implode(', ', $this->restConfig->allowedCorsHeaders);
        $this->allowedMethods = implode(', ', $this->restConfig->allowedCorsMethods);

        $this->setFormat($this->restConfig->restDefaultFormat);

        // Check for CORS access request
        if ($this->restConfig->checkCors === true) {
            $this->checkCors();
        }

        $this->model = \Config\Database::connect();
    }

    /**
     * Checks allowed domains, and adds appropriate headers for HTTP access control (CORS)
     *
     * @access protected
     * @return void
     */
    protected function checkCors()
    {
        // If we want to allow any domain to access the API
        if ($this->restConfig->allowAnyCorsDomain === true) {
            $this->origin = '*';
            // $this->setCorsHeaders();
            // $this->setCorsHeaders($this->origin, $this->allowedHeaders, $this->allowedMethods);
        } else {
            // We're going to allow only certain domains access
            // Store the HTTP Origin header
            $request = \Config\Services::request();
            $originHeader = $request->getHeader('Origin');

            if ($originHeader === null) {
                $this->origin = '';
                $this->allowedHeaders = '';
                $this->allowedMethods = '';
            } else {
                $origin = $originHeader->getValue();
                // If the origin domain is in the allowedCorsOrigins list, then add the Access Control headers
                if (in_array($origin, $this->restConfig->allowedCorsOrigins)) {
                    $this->origin = $origin;
                    // $this->setCorsHeaders();
                    //$this->setCorsHeaders($this->origin, $this->allowedHeaders, $this->allowedMethods);
                } else {
                    $this->origin = '';
                    $this->allowedHeaders = '';
                    $this->allowedMethods = '';
                    // $this->setCorsHeaders('', '', '');
                    // return $this->fail('Acceso Denegado');
                }
            }
        }

        // seteamos las cabeceras
        $this->setCorsHeaders();
    }

    /**
     * Options function
     *
     * @return bool
     */
    public function options()
    {
        return true;
    }

    /**
     * Set Cors Heardes function
     * 
     * @access protected
     * @return void
     */
    private function setCorsHeaders()
    {
        $response = \Config\Services::response();
        $response->setHeader('Access-Control-Allow-Origin', $this->origin)
            ->setHeader('Access-Control-Allow-Headers', strtolower($this->allowedHeaders))
            ->setHeader('Access-Control-Allow-Methods', $this->allowedMethods);
    }

    // private function setCorsStatusCode(int $statusCode = 200)
    // {
    //     $response = \Config\Services::response();
    //     return $response->setStatusCode($statusCode);
    // }

    /**
     * Respond Rest function
     *
     * @param boolean $status
     * @param string $message
     * @param array $data
     * @param integer $codeStatus
     * @param string $messageStatus
     * @return mixed
     */
    public function respondRest(bool $status = true, string $message = 'Ok', array $data = [], int $codeStatus = 200, string $messageStatus = '')
    {
        $dataResponse = [
            $this->restConfig->restStatusFieldName => $status,
            $this->restConfig->restMessageFieldName => $message,
            $this->restConfig->restDataFieldName => $data,
        ];

        return $this->respond($dataResponse, $codeStatus, $messageStatus);
    }

    /**
     * RespondRestError400 function
     *
     * @param string $message
     * @param array $data
     * @param string $messageStatus
     * @return void
     */
    public function respondRestError400(string $message = 'Error 400', array $data = [], string $messageStatus = '')
    {
        return $this->respondRest(false, $message, $data, 400, $messageStatus);
    }

    /**
     * RespondRestError401 function
     *
     * @param string $message
     * @param array $data
     * @param string $messageStatus
     * @return void
     */
    public function respondRestError401(string $message = 'Error 401', array $data = [], string $messageStatus = '')
    {
        return $this->respondRest(false, $message, $data, 401, $messageStatus);
    }

    
    /**
     * RespondRestSuccess function
     *
     * @param string $message
     * @param array $data
     * @param string $messageStatus
     * @return void
     */
    public function respondRestSuccess(string $message = 'Success', array $data = [], string $messageStatus = '')
    {
        return $this->respondRest(true, $message, $data, 200, $messageStatus);
    }

}
