<?php

function jsonFileToArray($archivo){

    /* Comprobar si existe el archivo */
    if(!is_file($archivo)) return false;

    /* Cargar el contenido del archivo */
    $contenido = file_get_contents($archivo);
    if($contenido === false) return false;

    //Luego lo codificamos a UTF-8 si es necesario
    //$con = utf8_encode($contenido);

    /* Convertir el contenido a un array */
    $datos = json_decode($contenido, true);
    if(is_null($datos)) return false;

    $hay = count($datos);
    echo sprintf('<p>Total Registros <strong> %s </strong></p>', $hay);
    return $datos;

}

/* Ejemplo de uso */
/* ******************************************* */
$datos = jsonFileToArray("c_MetodoPago.json");
$tabla="c_metodopago";
//print("<pre>".print_r($datos, true)."</pre>");
echo'
<table>
   <thead>
   <tr>
      <th>ID</th>
      <th>Descripcion</th>
      <th>fechaInicioDeVigencia</th>
      <th>fechaFinDeVigencia</th>
   </tr>
   </thead>
   <tbody>';

   foreach($datos as $valor){
        echo "<tr>";
        echo "<td>$valor[id]</td>";
        echo "<td>$valor[descripcion]</td>";
        echo "<td>$valor[fechaInicioDeVigencia]</td>";
        echo "<td>$valor[fechaFinDeVigencia]</td>";
        echo "</tr>";
        $fecha= $valor["fechaInicioDeVigencia"];
        $fechainversa=implode('-', array_reverse(explode('-', $fecha)));
        $data = array(
            "id_metodopago"      =>$valor['id'],
            "descripcionmp"      =>$valor["descripcion"],
            "fechainiciovigencia"=>$fechainversa,
            "version"            =>4.00,
            "ultusuario"         =>5
        );
        GuardarDatos($tabla, $data);
    };

 echo'</tbody>
</table>';
/* ******************************************* */
function GuardarDatos($tabla, $data){
$pdo=getPDO();

$sql="INSERT INTO $tabla (id_metodopago, descripcionmp, fechainiciovigencia, version, ultusuario) VALUES (:id_metodopago, :descripcionmp, :fechainiciovigencia, :version, :ultusuario)";

    try{

        //$stmt = Conexion::conectar()->prepare($sql);
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id_metodopago",      $data["id_metodopago"], PDO::PARAM_STR);
        $stmt->bindParam(":descripcionmp",      $data["descripcionmp"], PDO::PARAM_STR);
        $stmt->bindParam(":fechainiciovigencia",$data["fechainiciovigencia"], PDO::PARAM_STR);
        $stmt->bindParam(":version",            $data["version"], PDO::PARAM_STR);
        $stmt->bindParam(":ultusuario",         $data["ultusuario"], PDO::PARAM_INT);


        if($stmt->execute()){

            echo "ok";

        }else{

            echo "error";
            echo "<br/>";
        
        }

        $stmt = null;

    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }

}



/*================ ABRE LA BASE DE DATOS =============== */
function getPDO(){
	//$mysqlDatabaseName =defined('DATABASE')?DATABASE:null;
    try{
		$pdo = new PDO('mysql:host=localhost;dbname=inventario', 'root', '');
		//$pdo = new PDO("mysql:host=localhost;dbname=$mysqlDatabaseName","akordova_root","Super6908");
		$pdo->exec("set names utf8");
        return $pdo;
    }catch (PDOException $e) {
		print "¡Error!: <br/>";
		//die();
        return null;
    }
}
/*========================================================= */
