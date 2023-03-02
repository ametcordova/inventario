<?php
date_default_timezone_set('America/Mexico_City');
require_once "../modelos/conexion.php";
require_once '../funciones/funciones.php';

// Definimos las credenciales de conexión a la base de datos
$host = 'localhost'; // Cambia esto por tu nombre de host
$user = 'root'; // Cambia esto por tu nombre de usuario de la base de datos
$password = ' '; // Cambia esto por tu contraseña de la base de datos
$dbname = 'inventario'; // Cambia esto por el nombre de tu base de datos

// Definimos el nombre del archivo de respaldo
$backup_file = $dbname . date("Ymd-His") . '.sql';
try{
    // Ejecutamos el comando para hacer el respaldo
    //$command = "mysqldump --opt -h$host -u$user -p$password $dbname > $backup_file";
    $command = "mysqldump --host=$host --user=$user --password=$password --opt $dbname > $backup_file";
    //$command = "mysqldump --opt -h{$host} -u{$user} -p{$password} {$dbname} --single-transaction --quick --lock-tables=false > {$backup_file}";
    //$command = "mysqldump --host=localhost --user=root --password='' --opt inventario --single-transaction --quick --lock-tables=false > $backup_file";
    system($command, $resultado);

    switch($resultado){
        case 0:
        echo '<p style="color:white;">La base de datos <b>' .$dbname .'</b> se ha almacenado correctamente en la siguiente ruta '.getcwd().'/' .'</b></p>';
		break;
		usleep(3000000);
        case 1:
        echo '<p style="color:white;">Se ha producido un error al exportar <b>' .$dbname .'</b> a '.getcwd().'/' .$backup_file.'</b></p>';
		sleep(3);
        break;
        case 2:
        echo 'Se ha producido un error de exportación, compruebe la siguiente información: <br/><br/><table><tr><td>Nombre de la base de datos MySQL:</td><td><b>' .$dbname .'</b></td></tr><tr><td>Nombre de usuario MySQL:</td><td><b>' .$dbname .'</b></td></tr><tr><td>Contraseña MySQL:</td><td><b>NOTSHOWN</b></td></tr><tr><td>Nombre de host MySQL:</td><td><b>' .$host .'</b></td></tr></table>';
		sleep(3);
        break;
        default;
        echo 'Todo bien';
        break;
    }	

    // Comprimimos el archivo de respaldo
     $zip_file = $backup_file.'.zip';
     $zip = new ZipArchive();
    
     //Abrimos el archivo Zip para añadir el contenido
     if($zip->open($zip_file, ZipArchive::CREATE)===true) {
         $zip->addFile($backup_file, $backup_file);
         $zip->close();
     }else{
        json_output(json_build(403, null, 'No se creo el archivo ZIP'));
     }

    if (file_exists($zip_file)) {
        // Descargamos el archivo comprimido
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . basename($zip_file));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        //header('Content-Length: '.filesize($zip_file));   // con esta instruccion marca error
        readfile($zip_file);
    } else {
        echo "Error al descargar el archivo comprimido";
    }
    // Borramos los archivos temporales
    //unlink($backup_file);
    unlink($zip_file);

} catch (Exception $e) {
    json_output(json_build(403, null, $e->getMessage()));
}    
