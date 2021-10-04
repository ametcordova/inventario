<?php
if (isset($_FILES['uploadedFile'])) {
    $archivo = $_FILES['uploadedFile'];
    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
	$time = time();
    $idEntrada="{$_POST['numero_entrada']}";
    $nombre = $idEntrada."{$_POST['nombre_archivo']}_$time.$extension";
    if (move_uploaded_file($archivo['tmp_name'], "files_up/$nombre")) {
        echo 1;
    } else {
        echo 0;
    }
}
?>
 