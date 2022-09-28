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
$datos = jsonFileToArray("c_Moneda.json");
$tabla="c_moneda";
//print("<pre>".print_r($datos, true)."</pre>");
echo'
<table>
   <thead>
   <tr>
      <th>ID</th>
      <th>Descripcion Moneda</th>
      <th>Decimales</th>
      <th>Porcentaje</th>
      <th>fechaInicioDeVigencia</th>
      <th>fechaFinDeVigencia</th>
   </tr>
   </thead>
   <tbody>';

   foreach($datos as $valor){
        echo "<tr>";
        echo "<td>$valor[id]</td>";
        echo "<td>$valor[descripcion]</td>";
        echo "<td>$valor[decimales]</td>";
        echo "<td>$valor[porcentajeVariacion]</td>";
        echo "<td>$valor[fechaInicioDeVigencia]</td>";
        echo "<td>$valor[fechaFinDeVigencia]</td>";
        echo "</tr>";
        $fecha= $valor["fechaInicioDeVigencia"];
        $fechainversa=implode('-', array_reverse(explode('-', $fecha)));
        $data = array(
            "id_moneda"             =>$valor['id'],
            "descripcion"           =>$valor["descripcion"],
            "decimales"             =>$valor['decimales'],
            "porcentajevariacion"   =>$valor["porcentajeVariacion"]==""?"NULL":$valor["porcentajeVariacion"]*100,
            "fechainiciodevigencia" =>$fechainversa,
            "ultusuario"            =>5
        );
        GuardarDatos($tabla, $data);
    };

 echo'</tbody>
</table>';
/* ******************************************* */
function GuardarDatos($tabla, $data){
$pdo=getPDO();
$version=4.0;
$sql="INSERT INTO $tabla (id_moneda, descripcion, decimales, porcentajevariacion, fechainiciodevigencia, version, ultusuario) VALUES (:id_moneda, :descripcion, :decimales, :porcentajevariacion, :fechainiciodevigencia, :version, :ultusuario)";

    try{

        //$stmt = Conexion::conectar()->prepare($sql);
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id_moneda", $data["id_moneda"], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $data["descripcion"], PDO::PARAM_STR);
        $stmt->bindParam(":decimales", $data["decimales"], PDO::PARAM_INT);
        $stmt->bindParam(":porcentajevariacion", $data["porcentajevariacion"], PDO::PARAM_STR);
        $stmt->bindParam(":fechainiciodevigencia", $data["fechainiciodevigencia"], PDO::PARAM_STR);
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
