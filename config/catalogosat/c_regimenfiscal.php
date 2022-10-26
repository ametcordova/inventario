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
$datos = jsonFileToArray("c_RegimenFiscal.json");
$tabla="c_regimenfiscal";
//print("<pre>".print_r($datos, true)."</pre>");
echo'
<table>
   <thead>
   <tr>
      <th>ID</th>
      <th>Descripcion</th>
      <th>Fisica</th>
      <th>Moral</th>
      <th>fechaInicioDeVigencia</th>
      <th>fechaFinDeVigencia</th>
   </tr>
   </thead>
   <tbody>';

   foreach($datos as $valor){
        echo "<tr>";
        echo "<td>$valor[id]</td>";
        echo "<td>$valor[descripcion]</td>";
        echo "<td>$valor[fisica]</td>";
        echo "<td>$valor[moral]</td>";
        echo "<td>$valor[fechaDeInicioDeVigencia]</td>";
        echo "<td>$valor[fechaDeFinDeVigencia]</td>";
        echo "</tr>";
        $fechai= $valor["fechaDeInicioDeVigencia"];
        $fechainversai=implode('-', array_reverse(explode('-', $fechai)));
        $fechaf= $valor["fechaDeFinDeVigencia"];
        $fechainversaf=implode('-', array_reverse(explode('-', $fechaf)));
        $data = array(
            "id"                        =>intval($valor['id']),
            "descripcion"               =>$valor["descripcion"],
            "fisica"                    =>$valor['fisica'],
            "moral"                     =>$valor["moral"],
            "fechadeiniciodevigencia"   =>$fechainversai,
            "fechadefindevigencia"      =>$fechainversaf,
            "ultusuario"                =>5
        );
        GuardarDatos($tabla, $data);
    };

 echo'</tbody>
</table>';
/* ******************************************* */
function GuardarDatos($tabla, $data){
$pdo=getPDO();
$version=4.0;
$sql="INSERT INTO $tabla (id, descripcion, fisica, moral, fechadeiniciodevigencia, fechadefindevigencia, version, ultusuario) VALUES (:id, :descripcion, :fisica, :moral, :fechadeiniciodevigencia, :fechadefindevigencia, :version, :ultusuario)";

    try{

        //$stmt = Conexion::conectar()->prepare($sql);
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);
        $stmt->bindParam(":descripcion", $data["descripcion"], PDO::PARAM_STR);
        $stmt->bindParam(":fisica", $data["fisica"], PDO::PARAM_STR);
        $stmt->bindParam(":moral", $data["moral"], PDO::PARAM_STR);
        $stmt->bindParam(":fechadeiniciodevigencia", $data["fechadeiniciodevigencia"], PDO::PARAM_STR);
        $stmt->bindParam(":fechadefindevigencia", $data["fechadefindevigencia"], PDO::PARAM_STR);
        $stmt->bindParam(":version", $version, PDO::PARAM_STR);
        $stmt->bindParam(":ultusuario", $data["ultusuario"], PDO::PARAM_INT);


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
