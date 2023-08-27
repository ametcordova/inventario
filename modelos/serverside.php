<?php
require_once "conexion.php";

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
//header('Connection: keep-alive');
header('Access-Control-Allow-Origin: *');

ob_end_clean();
//flush();

$tabla="usuarios";
$users=array();
$redflag = '';
$stmt = Conexion::conectar()->prepare("SELECT id, usuario, (SELECT COUNT(*) FROM usuarios WHERE logueado>0) as logueados FROM usuarios WHERE logueado>0");
$stmt -> execute();
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Se agrega la lógica para obtener los datos que se desea enviar
if(!isset($resultado) || count($resultado)==0){
    //por si abrio una ventana de incognito y cerro sesion, quedo en cero o hay error
    $users =1;
}else{
    foreach($resultado as $key => $value){
        array_push($users, $value);
    }
}

$usuarios=json_encode($users);
if(strcmp($redflag, $usuarios)!== 0){      //si los strings no coinciden
    echo "data: {$usuarios}\n\n";
    $redflag = $usuarios;
}
//echo "retry: 15000\n\n";        //cada 15000=15 segundos envia el stream 

//ob_end_flush();
ob_flush();
flush();    

sleep(10);

?>
