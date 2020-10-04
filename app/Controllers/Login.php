<?php

namespace App\Controllers;

// use Libraries\RestController;
use App\Models\UsersModel;
use Libraries\RestController;

/**
 * Login class
 * 
 * @author Oscar Jimenez <oscarato1993@gmail.com>
 * 
 */
class Login extends RestController
{

    /**
     * User Model
     *
     * @var UsersModel
     */
    public $usersModel;
    
    /**
     * __construct function
     */
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->usersModel = new UsersModel();
    }


    /**
     * ModelName variable
     *
     * @var string
     */
    protected $modelName = 'App\Models\LoginModel';

    /**
     * Format variable
     *
     * @var string
     */
    protected $format    = 'json';

    //--------------------------------------------------------------------

    /**
     * @api {post} /login/signin Petición para login
     * @apiName Login
     * @apiGroup login
     * 
     * @apiParam {String} Email Identificación del empleado
     * @apiParam {String} Password Nombre del empleado
     * 
     * @apiSuccess {JSON} JSON con mensaje de logueo exitosa y Web Token
     * 
     * @apiHeader {String} Content-Type multipart/form-data.
     * 
     * @apiError BadRequest Json Error en los datos Ingresado, usuario no existe.
     */
    public function signIn()
    {
        try {
                       
            //
            $request = \Config\Services::request();

            //consultamos los datos enviados por POST
            $data = $request->getPost();

            //validamos los datos
            $validation =  \Config\Services::validation();
            $rules = [
                'email' => ['label' => 'Email', 'rules' => 'required|valid_email'],
                'password' => ['label' => 'Password', 'rules' => 'required'],
            ];
            if ($validation->setRules($rules)->withRequest($request)->run()) {

                //consultamos el usuario en la DB
                $user = $this->usersModel->getData(['email' => $data['email']]);

                if ($user) {

                    //comparamos el password
                    if (password_verify($data['password'], $user['password'])) {

                        // preparamos los datos
                        $userArray = [
                            'id' => $user['id'],
                            'name' => $user['name'],
                            'email' => $user['email'],
                            'status' => $user['status']
                        ];
                        

                        //generamos el token
                        $jwt = \Config\Services::jwt();
                        $arrayClaim = [
                            'id' => $user['id']
                        ];
                        $token = $jwt->buildToken()->setClaim($arrayClaim)->getToken(true);

                        // registramos en el login
                        $this->model->insert(['users_id' => $user['id'], 'token' => $token]);

                        // actualizamos en users
                        $this->usersModel->update($user['id'], ['token' => $token]);

                        return $this->respondRest(true, 'Bienvenido al sistema.', ['token' => $token, 'user' => $userArray]);
                    } else {
                        return $this->respondRest(false, 'Password Incorrecto.', [], 401);
                    }
                } else {
                    return $this->respondRest(false, 'Email No Encontrado.', [], 401);
                }
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
     * Sign Out function
     *
     * PSCIN-4 Cerrar Sesión
     * 
     * @return void
     */
    public function signOut()
    {
        try {
            $jwt = \Config\Services::jwt();
            $resp = $jwt->validateSession();

            if ($resp['status']) {
                $user = $resp['data'];
                // actualizamos a vacio el token del usuario
                // $this->usersModel->update($user['id'], ['token' => '']);
                // return $this->respond(['msg' => 'Salida Exitosa.']);
                $login = $this->model->getData(['users_id' => $user['id']], 'id');
                $this->model->update($login['id'], [ 'request' => '1', 'token' => null]);
                return $this->respondRest(true, 'Salida Exitosa.');
            } else {
                return $this->respondRest(false, $resp['msg'], [], 401);
            }
        } catch (\Exception $e) {
            return $this->respondRest(false, $e->getMessage(), [], 401);
        }
    }
    
    /**
     * getToken function
     * 
     * Esta función obtiene el login creado desde las redes sociales
     *
     * @return void
     */
    public function getToken(){
        
        $request = \Config\Services::request();

        //consultamos los datos enviados por GET
        $data = $request->getGet();

        // buscamos el login solicitado por el uuid de la petición
        $login = $this->model->getData(['uuid_device' => $data['id'], 'request' => '1']);

        if(empty($login) || is_null($login['users_id']) || empty($login['token'])){
            return $this->respondRest(false, 'No hay registro.', []);
        }
        
        // Id del usuario
        $idUser = $login['users_id'];

        //token 
        $tokenClanin = $login['token'];
        
        // consultamos el usuario
        $userData = $this->usersModel->getData(['id' => $idUser ]);

        // preparamos los datos
        $userArray = [
            'id' => $userData['id'],
            'name' => $userData['name'],
            'email' => $userData['email'],
            'status' => $userData['status']
        ];

        // consultamos el perfil del usuario
        $userProfile = $this->profileModel->getProfileDetail($idUser);

        // datos de la red social
        $userNetworkData = $this->usersSocialNetworksModel->getData(['users_id' => $idUser ]);

        // preparamos los datos
        $profileArray = [
            'id' => $userProfile['id'],
            'first_name' => $userProfile['first_name'],
            'last_name' => $userProfile['last_name'],
            'photo' => empty($userProfile['photo']) ?  $userNetworkData['network_user_picture_url'] : $userProfile['photo'],
            'email' => $userProfile['email'],
            'celular' => $userProfile['celular'],
            'birth_date' => $userProfile['birth_date'],
            'description' => $userProfile['description'],
            'identities_id' => $userProfile['identities_id'],
            'identities_name' => $userProfile['identities_name'],
            'cities_id' => $userProfile['cities_id'],
            'cities_name' => $userProfile['cities_name'],
            'departments_id' => $userProfile['departments_id'],
            'departments_name' => $userProfile['departments_name'],
            'gender_id' => $userProfile['gender_id'],
            'gender_name' => $userProfile['gender_name'],
        ];

        // actualizamos el request para que no vuelva a ser obtenido
        $this->model->update($login['id'], [ 'request' => '0']);

        return $this->respondRest(true, 'Bienvenido al sistema.', ['token' => $tokenClanin, 'user' => $userArray, 'profile' => $profileArray]);
    }
}
