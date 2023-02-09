<?php

namespace Baulphp;

class DBClass
{

    const HOST = 'localhost';
    const USERNAME = 'root';
    const PASSWORD = '';
    const DATABASENAME = 'inventario';
    private $conexion;


    function __construct()
    {
        $this->conexion = $this->getConnection();
    }


    public function getConnection()
    {
        $conexion = new \mysqli(self::HOST, self::USERNAME, self::PASSWORD, self::DATABASENAME);

        if (mysqli_connect_errno()) {
            trigger_error("Problem with connecting to database.");
        }

        $conexion->set_charset("utf8");
        return $conexion;
    }

    public function select($query, $paramType = "", $paramArray = array())
    {
        $stmt = $this->conexion->prepare($query);

        if (! empty($paramType) && ! empty($paramArray)) {

            $this->bindQueryParams($stmt, $paramType, $paramArray);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $resultset[] = $row;
            }
        }

        if (! empty($resultset)) {
            return $resultset;
        }
    }

    /**
     * Función insertar a MySQL
     */
    public function insert($query, $paramType, $paramArray)
    {
        $stmt = $this->conexion->prepare($query);
        $this->bindQueryParams($stmt, $paramType, $paramArray);

        $stmt->execute();
        $insertId = $stmt->insert_id;
        return $insertId;
    }

    /**
     * Ejecución de la consulta
     */
    public function execute($query, $paramType = "", $paramArray = array())
    {
        $stmt = $this->conexion->prepare($query);

        if (! empty($paramType) && ! empty($paramArray)) {
            $this->bindQueryParams($stmt, $paramType, $paramArray);
        }
        $stmt->execute();
    }

    /**
     * 1. Prepara la vinculación de parámetros
     * 2. Vincular parámetros a la declaración sql
     *
     */
    public function bindQueryParams($stmt, $paramType, $paramArray = array())
    {
        $paramValueReference[] = & $paramType;
        for ($i = 0; $i < count($paramArray); $i ++) {
            $paramValueReference[] = & $paramArray[$i];
        }
        call_user_func_array(array($stmt,'bind_param'), $paramValueReference);
    }

    /**
		* Obtener resultados de la base de datos
     */
    public function getRecordCount($query, $paramType = "", $paramArray = array())
    {
        $stmt = $this->conexion->prepare($query);
        if (! empty($paramType) && ! empty($paramArray)) {

            $this->bindQueryParams($stmt, $paramType, $paramArray);
        }
        $stmt->execute();
        $stmt->store_result();
        $recordCount = $stmt->num_rows;

        return $recordCount;
    }
}