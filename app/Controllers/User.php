<?php

namespace App\Controllers;

use Libraries\RestController;
use App\Models\{
    LoginModel
};

/**
 * Users class
 * 
 * @author Oscar Jimenez <oscarato1993@gmail.com>
 * 
 */
class User extends RestController
{

    /**
     * ModelName variable
     *
     * @var string
     */
    protected $modelName = 'App\Models\UsersModel';

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
     * @var LoginModel
     */
    public $loginModel;
    
    //--------------------------------------------------------------------

    /**
     * __construct function
     */
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->loginModel = new LoginModel();

        // helper de nitificaciones push
        helper('notification');
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
                'name' => ['label' => 'Nombre', 'rules' => 'required|min_length[3]'],
                'email' => ['label' => 'Email', 'rules' => 'required|valid_email|is_unique[' . $this->model->table . '.email]'],
                'password' => ['label' => 'Password', 'rules' => 'required|min_length[5]'],
                'cpassword' => ['label' => 'Confirmación de Password', 'rules' => 'required|matches[password]'],
            ];
            if ($validation->setRules($rules)->withRequest($request)->run()) {

                // dd(CI_DEBUG);

                //iniciamos la transaccion
                $this->model->db->transStart();

                $dataSave = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                    'status' => 1, // activo
                ];

                //consultamos el usuario en la DB
                $id = $this->model->insert($dataSave);

                //generamos el token
                $jwt = \Config\Services::jwt();
                $arrayClaim = [
                    'id' => $id,
                ];
                $token = $jwt->buildToken()->setClaim($arrayClaim)->getToken(true);

                //actualizamos el token en la BD
                $this->model->update($id, ['token' => $token]);

                // realizamos el registro en login
                $this->loginModel->insert(['users_id' => $id, 'token' => $token]);

                // preparamos los datos del perfil
                $profile = [
                    'id' => $id,
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'status' => 1,
                ];

                //finalizamos la transaccion                                                                                                                                                                                                                         
                $this->model->transComplete();

                return $this->respondRest(true, 'Registro Exitoso, Bienvenido al sistema.', ['token' => $token, 'profile' => $profile]);
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
     * Profile function
     *
     * @return void
     */
    public function profile()
    {
        try {

            $jwt = \Config\Services::jwt();
            $resp = $jwt->validateSession();

            if ($resp['status']) {
                $user = $resp['data'];

                //consultamos los datos del usuario
                $userResult = $this->model->getProfile($user['id']);

                if (is_null($userResult['photo']) || empty($userResult['photo'])) {
                    $userResult['photo'] = base_url('uploads/users/default.png');
                } else {
                    $userResult['photo'] = base_url('uploads/' . $userResult['photo']);
                }

                $profile = [
                    'id' => $userResult['id'],
                    'name' => $userResult['name'],
                    'nickname' => $userResult['nickname'],
                    'email' => $userResult['email'],
                    'phone' => $userResult['telephone'],
                    'cityid' => $userResult['citie_id'],
                    'stateid' => $userResult['deparment_id'],
                    'photos' => [
                        $userResult['photo']
                    ]
                ];

                return $this->respondRest(true, 'Datos del Perfil del Usuario .', $profile);
            } else {
                //return $this->failValidationError($resp['msg']);userResult
                return $this->respondRest(false, $resp['msg'], [], 401);
            }
        } catch (\Exception $e) {
            //return $this->failValidationError($e->getMessage());
            return $this->respondRest(false, $e->getMessage(), [], 401);
        }
    }

    //--------------------------------------------------------------------

}
