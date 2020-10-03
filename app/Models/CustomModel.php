<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * CustomModel class
 * 
 * @author Oscar Jimenez <oscarato1993@gmail.com>
 * 
 */
class CustomModel extends Model
{
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    //--------------------------------------------------------------------

    /**
     * Get Data function
     *
     * @param array $where
     * @param string $select
     * @param boolean $row
     * @return mixed
     */
    public function getData(array $where = [], string $select = null, bool $row = true)
    {
        $query = $this->builder();
        $select = is_null($select) ? '*' : $select;
        $query->select($select);
        if (!empty($where)) {
            $query->where($where);
        }
        if ($this->tempUseSoftDeletes === true) {
            $query->where($this->table . '.' . $this->deletedField, null);
        }

        // echo $query->getCompiledSelect();
        // exit();

        $result = $query->get();

        return ($row) ? $result->getRow(0, $this->tempReturnType) : $result->getResult($this->tempReturnType);
        // return ($row) ? $result->getRowArray() : $result->getResultArray();
    }
}
