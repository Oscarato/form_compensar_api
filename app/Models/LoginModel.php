<?php

namespace App\Models;

use App\Models\CustomModel;

/**
 * LoginModel class
 * 
 * @author Oscar Jimenez <oscarato1993@gmail.com>
 * 
 */
class LoginModel extends CustomModel
{
    protected $table      = 'login';
    protected $primaryKey = 'id';
    protected $allowedFields = ['users_id', 'uuid_device', 'token', 'request', 'expiration'];

    //--------------------------------------------------------------------

    /**
     * Fecth function
     * 
     * @param integer $userProfileId
     * @param integer $limit
     * @param integer $offset
     * @return array
     */
    public function fecth($users_id, $uuid_device)
    {
        $select = "login.id";

        $query = $this->builder()
            ->from($this->table, true)
            ->select($select)
            ->where(['login.users_id' => $users_id])
            ->where('login.uuid_device !=', $uuid_device);
            
        // echo $query->getCompiledSelect();
        // exit();
        $result = $query->get();

        return $result->getResultArray();
    }
}
