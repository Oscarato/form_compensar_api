<?php

namespace App\Controllers;

use Libraries\RestController;
use App\Models\{
    EmployeesModel
};

/**
 * Users class
 * 
 * @author Oscar Jimenez <oscarato1993@gmail.com>
 * 
 */
class Employee extends RestController
{

    /**
     * ModelName variable
     *
     * @var string
     */
    protected $modelName = 'App\Models\EmployeesModel';

    /**
     * Format variable
     *
     * @var string
     */
    protected $format    = 'json';

    /**
     * Login Model
     * 
     * Login Model
     *
     * @var EmployeesModel
     */
    public $employeesModel;
    
    //--------------------------------------------------------------------

    /**
     * __construct function
     */
    public function __construct()
    {
        parent::__construct();
        helper('request_put');
        //Do your magic here
        $this->employeesModel = new EmployeesModel();
        
    }

    //--------------------------------------------------------------------

    /**
     * @api {get} /employees Peticion lista de empleados
     * @apiName Employees
     * @apiGroup Employess
     *
     * @apiParam {Number} limit Limite de lista
     * @apiParam {Number}  offset  Posicion de inicio de datos para lista
     * @apiParam {JSON} filter  json de filtro 
     * 
     *
     * @apiSuccess {JSON} json con la lista de empleados
     * 
     */
    public function fetch()
    {
        try {
            $jwt = \Config\Services::jwt();
            $resp = $jwt->validateSession();
            if ($resp['status']) {

                $user = $resp['data'];
                //
                $request = \Config\Services::request();
                
                //consultamos los datos enviados por POST
                $dataGet = $request->getGet();

                $limit = (isset($dataGet['limit'])) ? (int) $dataGet['limit'] : 20;
                $offset = (isset($dataGet['offset'])) ? (int) $dataGet['offset'] : 0;
                $query = null;
                $filterQ = json_decode($dataGet['filter'] ?? '[]', JSON_OBJECT_AS_ARRAY);

                if(!empty($filterQ['query'])){
                    $query = $filterQ['query'];
                }

                //iniciamos la transaccion
                $this->model->transStart();

                //consultamos los grupos
                $employees = $this->employeesModel->fecthAll($user['id'], $limit, $offset, $query);
                
                //finalizamos la transaccion
                $this->model->transComplete();

                return $this->respondRest(((bool) count($employees) > 0), 'Listado de empleados.', $employees);
            } else {
                return $this->respondRest(false, $resp['msg'], [], 401);
            }
        } catch (\Exception $e) {
            return $this->respondRest(false, $e->getMessage(), [], 401);
        }
    }

    //--------------------------------------------------------------------

    /**
     * Add function
     *
     * @return void
     */
    public function add()
    {
        try {

            //
            $request = \Config\Services::request();

            //consultamos los datos enviados por POST
            $data = $request->getPost();

            //validamos los datos
            $validation =  \Config\Services::validation();
            $rules = [
                'identification' => ['label' => 'Identificación', 'rules' => 'required'],
                'name' => ['label' => 'Email', 'rules' => 'required'],
                'lastname' => ['label' => 'Apellido', 'rules' => 'required'],
                'cat' => ['label' => 'Categoria', 'rules' => 'required'],
                'age' => ['label' => 'Edad', 'rules' => 'required'],
                'job' => ['label' => 'Cargo', 'rules' => 'required'],
                'status' => ['label' => 'Estado', 'rules' => 'required'],
            ];
            if ($validation->setRules($rules)->withRequest($request)->run()) {
                
                //iniciamos la transaccion
                $this->model->transStart();

                $dataSave = [
                    'identification'    =>  $data['identification'],
                    'name'              =>  $data['name'],
                    'lastname'          =>  $data['lastname'],
                    'cat'               =>  $data['cat'],
                    'age'               =>  $data['age'],
                    'job'               =>  $data['job'],
                    'status'            =>  $data['status'],
                ];

                //consultamos el usuario en la DB
                $this->employeesModel->insert($dataSave);
                
                //finalizamos la  transaccion                                         
                $this->model->transComplete();

                return $this->respondRest(true, 'Registro Exitoso');
            } else {
                $errors = $validation->getErrors();
                return $this->respondRest(false, 'Error en los datos Ingresados, ' . implode(' - ', $errors), $errors, 400);
            }
        } catch (\Exception $e) {
            return $this->respondRest(false, $e->getMessage(), [], 401);
        }
    }

    //--------------------------------------------------------------------

    /**
     * Modifica un empleado
     *
     * @param Int $id
     * @return void
     */
    public function modify($id = null){
        try {
            
            //consultamos los datos enviados por POST
            $data = request_put();

            if(empty($data)){
                return $this->respondRestError400('No hay parámetros para actualizar.');
            }
            
            // verificamos si existe el empleado
            $employee = $this->employeesModel->getData([
                'id'    =>  $id
            ], 'id', false);

            if(empty($employee)){
                throw new \Exception('Error - El empleado no existe.');
            }

            //validamos los datos
            // $validation =  \Config\Services::validation();
            // $rules = [
            //     'identification'    =>  ['label' => 'Identificación'],
            //     'name'              =>  ['label' => 'Email'],
            //     'lastname'          =>  ['label' => 'Apellido'],
            //     'cat'               =>  ['label' => 'Categoria'],
            //     'age'               =>  ['label' => 'Edad'],
            //     'job'               =>  ['label' => 'Cargo'],
            //     'status'            =>  ['label' => 'Estado'],
            // ];
            // if ($validation->setRules($rules)->run($data)) {
                
                //iniciamos la transaccion
                $this->model->transStart();

                $data_up = array(
                    'identification',
                    'name',
                    'lastname',
                    'cat',
                    'age',
                    'job',
                    'status'
                );

                $dataSave = array();
                foreach ($data_up as $d) {
                    if(isset($data[$d])){
                        $dataSave[$d] =   $data[$d];
                    }
                }

                // $dataSave = [
                //     'identification'    =>  $data['identification'],
                //     'name'              =>  $data['name'],
                //     'lastname'          =>  $data['lastname'],
                //     'cat'               =>  $data['cat'],
                //     'age'               =>  $data['age'],
                //     'job'               =>  $data['job'],
                //     'status'            =>  $data['status'],
                // ];

                // print_r($dataSave);
                // exit;

                //consultamos el usuario en la DB
                $this->employeesModel->update($id, $dataSave);
                
                //finalizamos la  transaccion                                         
                $this->model->transComplete();

                return $this->respondRest(true, 'Registro Exitoso');

            // } else {
            //     $errors = $validation->getErrors();
            //     return $this->respondRest(false, 'Error en los datos Ingresados, ' . implode(' - ', $errors), $errors, 400);
            // }
        } catch (\Exception $e) {
            return $this->respondRest(false, $e->getMessage(), [], 401);
        }
    }

    //--------------------------------------------------------------------

    /**
     * Del function
     * 
     * @return void
     */
    public function delete($id = null)
    {
        try {
            $jwt = \Config\Services::jwt();
            $resp = $jwt->validateSession();
            if ($resp['status']) {
                
                // verificamos si existe el empleado
                $employee = $this->employeesModel->getData([
                    'id'    =>  $id
                ], 'id', false);

                if(empty($employee)){
                    throw new \Exception('Error - El empleado no existe.');
                }
                
                //iniciamos la transaccion
                $this->model->transStart();

                $this->employeesModel->delete($id);

                //finalizamos la transaccion
                $this->model->transComplete();

                return $this->respondRestSuccess('Empleado Eliminado Correctamente.');

            } else {
                return $this->respondRestError401($resp['msg']);
            }
        } catch (\Exception $e) {
            return $this->respondRestError401($e->getMessage());
        }
    }

    //--------------------------------------------------------------------

}
