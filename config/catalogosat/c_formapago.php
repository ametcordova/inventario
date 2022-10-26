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
$datos = jsonFileToArray("c_FormaPago.json");
$tabla="c_formapago";
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

   foreach($datos as $valor):
        echo "<tr>";
        echo "<td>$valor[id]</td>";
        echo "<td>$valor[descripcion]</td>";
        echo "<td>$valor[bancarizado]</td>";
        echo "<td>$valor[numeroDeOperacion]</td>";
        echo "<td>$valor[rFCDelEmisorDeLaCuentaOrdenante]</td>";
        echo "<td>$valor[cuentaOrdenante]</td>";
        echo "<td>$valor[patronParaCuentaOrdenante]</td>";
        echo "<td>$valor[rFCDelEmisorCuentaDeBeneficiario]</td>";
        echo "<td>$valor[cuentaDeBenenficiario]</td>";
        echo "<td>$valor[patronParaCuentaBeneficiaria]</td>";
        echo "<td>$valor[tipoCadenaPago]</td>";
        echo "<td>$valor[nombreDelBancoEmisorDeLaCuentaOrdenanteEnCasoDeExtranjero]</td>";
        echo "<td>$valor[fechaInicioDeVigencia]</td>";
        echo "<td>$valor[fechaFinDeVigencia]</td>";
        echo "</tr>";
        $fechai= $valor["fechaInicioDeVigencia"];
        $fechainversai=implode('-', array_reverse(explode('-', $fechai)));
        $fechaf= $valor["fechaFinDeVigencia"];
        $fechainversaf=$valor["fechaFinDeVigencia"]!=""?implode('-', array_reverse(explode('-', $fechaf))):"";
        $data = array(
            "id"                        =>$valor["id"],
            "descripcionformapago"      =>$valor["descripcion"],
            "bancarizado"               =>$valor["bancarizado"],
            "numerodeoperacion"         =>$valor["numeroDeOperacion"],
            "rfcdelemisordelactaord"    =>$valor["rFCDelEmisorDeLaCuentaOrdenante"],
            "cuentaordenante"           =>$valor["cuentaOrdenante"],
            "patroncuentaordenante"     =>$valor["patronParaCuentaOrdenante"],
            "rfcdelemisorctabenef"      =>$valor["rFCDelEmisorCuentaDeBeneficiario"],
            "cuentabeneficiario"        =>$valor["cuentaDeBenenficiario"],
            "patronctabeneficiaria"     =>$valor["patronParaCuentaBeneficiaria"],
            "tipocadenapago"            =>$valor["tipoCadenaPago"],
            "nombancoemisorctaordext"   =>$valor["nombreDelBancoEmisorDeLaCuentaOrdenanteEnCasoDeExtranjero"],
            "fechainiciovigencia"       =>$fechainversai,
            "fechafinvigencia"          =>$fechainversaf,
            "version"                   =>4.00,
            "ultusuario"                =>5
        );
        GuardarDatos($tabla, $data);
    endforeach;

 echo'</tbody>
</table>';
/* ******************************************* */
function GuardarDatos($tabla, $data){
$pdo=getPDO();
//$version=4.0;
//$sql="INSERT INTO $tabla (id, descripcionformapago, bancarizado, numerodeoperacion, rfcdelemisordelactaord, cuentaordenante, patroncuentaordenante, rfcdelemisorctabenef, cuentabeneficiario, patronctabeneficiaria, tipocadenapago, nombancoemisorctaordext, fechainiciovigencia, version, ultusuario) VALUES (:id, :descripcionformapago, :bancarizado, :numerodeoperacion, :rfcdelemisordelactaord, :cuentaordenante, :patroncuentaordenante, :rfcdelemisorctabenef, :cuentabeneficiario, :patronctabeneficiaria, :tipocadenapago, :nombancoemisorctaordext, :fechainiciovigencia, :version, :ultusuario)";
$sql="INSERT INTO $tabla (id, descripcionformapago, bancarizado, numerodeoperacion, rfcdelemisordelactaord, cuentaordenante, patroncuentaordenante, rfcdelemisorctabenef, cuentabeneficiario, patronctabeneficiaria, tipocadenapago, nombancoemisorctaordext, fechainiciovigencia, fechafinvigencia, version, ultusuario) VALUES (:id, :descripcionformapago, :bancarizado, :numerodeoperacion, :rfcdelemisordelactaord, :cuentaordenante, :patroncuentaordenante, :rfcdelemisorctabenef, :cuentabeneficiario, :patronctabeneficiaria, :tipocadenapago, :nombancoemisorctaordext, :fechainiciovigencia, :fechafinvigencia, :version, :ultusuario)";

    try{

        //$stmt = Conexion::conectar()->prepare($sql);
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id",                     $data["id"], PDO::PARAM_INT);
        $stmt->bindParam(":descripcionformapago",   $data["descripcionformapago"], PDO::PARAM_STR);
        $stmt->bindParam(":bancarizado",            $data["bancarizado"], PDO::PARAM_STR);
        $stmt->bindParam(":numerodeoperacion",      $data["numerodeoperacion"], PDO::PARAM_STR);
        $stmt->bindParam(":rfcdelemisordelactaord", $data["rfcdelemisordelactaord"], PDO::PARAM_STR);
        $stmt->bindParam(":cuentaordenante",        $data["cuentaordenante"], PDO::PARAM_STR);
        $stmt->bindParam(":patroncuentaordenante",  $data["patroncuentaordenante"], PDO::PARAM_STR);
        $stmt->bindParam(":rfcdelemisorctabenef",   $data["rfcdelemisorctabenef"], PDO::PARAM_STR);
        $stmt->bindParam(":cuentabeneficiario",     $data["cuentabeneficiario"], PDO::PARAM_STR);
        $stmt->bindParam(":patronctabeneficiaria",  $data["patronctabeneficiaria"], PDO::PARAM_STR);
        $stmt->bindParam(":tipocadenapago",         $data["tipocadenapago"], PDO::PARAM_STR);
        $stmt->bindParam(":nombancoemisorctaordext",$data["nombancoemisorctaordext"], PDO::PARAM_STR);
        $stmt->bindParam(":fechainiciovigencia",    $data["fechainiciovigencia"], PDO::PARAM_STR);
        $stmt->bindParam(":fechafinvigencia",       $data["fechafinvigencia"], PDO::PARAM_STR);
        $stmt->bindParam(":version",                $data["version"], PDO::PARAM_STR);
        $stmt->bindParam(":ultusuario",             $data["ultusuario"], PDO::PARAM_INT);


        if($stmt->execute()){

            echo "ok";

        }else{

            echo "error";
            echo "<br/>";
            var_dump($stmt);
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
