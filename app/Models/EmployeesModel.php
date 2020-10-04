<?php

namespace App\Models;

use App\Models\CustomModel;

/**
 * UsersModel class
 * 
 * @author Oscar Jimenez <oscarato1993@gmail.com>
 * 
 */
class EmployeesModel extends CustomModel
{
    protected $table      = 'employees';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_type', 'identification', 'name', 'lastname', 'cat', 'age', 'job', 'status'];

    //--------------------------------------------------------------------

    /**
     * FecthAll function
     * 
     * Consulta todos los usuarios creadas
     * 
     * @param integer $id
     * @param integer $limit
     * @param integer $offset
     * @return array
     */
    public function fecthAll(
        int $limit,
        int $offset
    ) {
        $query = $this->builder()
            ->from($this->table . ' AS us', true)
            ->select('id, id_type, identification, name, lastname, cat, age, job, status');

        if ($this->tempUseSoftDeletes === true) {
            $query->where('us.' . $this->deletedField, null);
        }

        $query->limit($limit, $offset);
        // echo $query->getCompiledSelect();
        // exit();
        $result = $query->get();

        return $result->getResultArray();
    }

    //--------------------------------------------------------------------

    /**
     * countAll function
     * 
     * Consulta todas las actividades creadas
     * 
     * @param integer $id
     * @return array
     */
    public function countAll($id) {
        $query = $this->builder()
            ->from($this->table . ' AS us', true)
            ->select('id')
            ->where('id <> '. $id);

        if ($this->tempUseSoftDeletes === true) {
            $query->where('us.' . $this->deletedField, null);
        }

        // $query->limit($limit, $offset);
        // echo $query->getCompiledSelect();
        // exit();
        return $query->countAllResults();

        // return $result->getResultArray();
    }

}
