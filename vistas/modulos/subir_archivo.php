<?php
/* https://www.html5rocks.com/es/tutorials/file/dndfiles// 
*  http://demo.webslesson.info/
*/

if (isset($_FILES['uploadedFile'])) {
    $archivo = $_FILES['uploadedFile'];
    $rutaarchivo="entradascarso/";
    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
	$time = time();
    $idEntrada="{$_POST['numero_entrada']}";
    //$nombre = $idEntrada."{$_POST['nombre_archivo']}_$time.$extension";
    $nombre = $idEntrada."_".$time."_{$_POST['nombre_archivo']}";
    if (move_uploaded_file($archivo['tmp_name'], "../".$rutaarchivo.$nombre)) {
        //echo 1;
        echo json_encode(array('status' => 1, 'nombre' => $nombre, 'rutaarchivo' => $rutaarchivo, 'extension' => $extension));
    } else {
        echo 0;
    }
}
?>
 