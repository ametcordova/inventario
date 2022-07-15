<?php
session_start();
//$path=getcwd();  //C:\xampp\htdocs\inventario\vistas\modulos


if (isset($_FILES['archivo']) && !empty($_FILES['archivo'])) {

    if($_FILES['size']>1048576*4) {
        $res = array(
            "err" => true,
            "status" => http_response_code(405),
            "statusText" => "Error: Archivo muy pesado",
            "files" => $_FILES["archivo"],
            'size' => $_FILES['archivo']['size']
          );
    
        echo json_encode($res);     
        die();
     }
    

    //var_dump($_FILES['uploadFile']);

    $archivo = $_FILES['archivo'];
    //$snamefile="{$_POST['archivo']}";
    $error = $_FILES["archivo"]["error"];
    $rutaarchivo="/archivos/repositorio/".$_SESSION['usuario']."/";
    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);

    if (!file_exists('../..'.$rutaarchivo)) {
        if(!mkdir('../..'.$rutaarchivo, 0777)) {
            echo json_encode(array('status' => 500, 'nombre' => $archivo, 'rutaarchivo' => $rutaarchivo, 'error' => 'Error al crear la carpeta'));
            exit();
        }
    }

    //Se verifica si la carpeta tiene permisos de escritura
    if (! is_writable('../..'.$rutaarchivo) || ! is_dir('../..'.$rutaarchivo)) {
        //echo json_encode(array('status' => 500, 'nombre' => $archivo, 'rutaarchivo' => $rutaarchivo, 'permisos' => 'sin permisos de guardar'));
        $res = array(
            "err" => true,
            "status" => http_response_code(500),
            "statusText" => "Error al subir el archivo $archivo",
            "files" => $_FILES["uploadFile"]
        );
        //exit();
    }

        //AQUI GUARDA EL ARCHIVO EN LA CARPETA EN EL DISCO
        //if (move_uploaded_file($archivo['tmp_name'], "../..".$rutaarchivo.$archivo)) {
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], "../..".$rutaarchivo.$_FILES['archivo']['name'])) {
            $res = array(
                "err" => false,
                "status" => http_response_code(201),
                "statusText" => $rutaarchivo,
                "files" => $_FILES["archivo"],
                "rutaarchivo" => $rutaarchivo
            );
        
            //echo json_encode(array('status' => 1, 'nombre' => $archivo, 'rutaarchivo' => $rutaarchivo, 'extension' => $extension));
        } else {
            
            $res = array(
                "err" => true,
                "status" => http_response_code(400),
                "statusText" => "Error al subir el archivo $archivo",
                "files" => $_FILES["uploadFile"]
            );
        
        }

    echo json_encode($res);

}else{
    $res = array(
        "err" => true,
        "status" => http_response_code(404),
        "statusText" => "Error: Archivo no existe",
        "file" => $_FILES["archivo"]
      );

      echo json_encode($res);
}
?>
 