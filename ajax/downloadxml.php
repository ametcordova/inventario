<?php

function descargarxml($folio, $serie, $rfcemisor){
    // $folio          = $_GET['datafolio'];
    // $dataserie      = $_GET['dataserie'];
    // $datarfcemisor  = $_GET['datarfcemisor'];

        $diractual= dirname(__DIR__);
        $namexml=$rfcemisor.'-'.$serie.$folio.'.xml';
        $archivoxml=$diractual."/ajax/salida/".$namexml;
        //echo $archivoxml;
        header('Content-Type: text/xml');
        header("Content-Disposition:attachment; filename=$namexml"); 
        header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
        header("Last-Modified: " .gmdate("D, d M Y H:i:s")." GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
         
        //echo file_get_contents("http://localhost/inventario/ajax/salida/DIGB980626MX3-A378.xml");
        file_get_contents($archivoxml);
    }

    function descargar($folio, $serie, $rfcemisor){
        if(!empty($folio)){
            $namexml=$rfcemisor.'-'.$serie.$folio.'.xml';
            $diractual= dirname(__DIR__);
            $filepath=$diractual."/ajax/salida/".$namexml;
            //$filepath = 'C:\\directorioPrivado\\'.$namexml;
     
            if(!empty($namexml) && file_exists($filepath)){
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.$namexml.'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Lenght: '.filesize($filepath));
                header('Content-Transfer-Encoding: binary');
                readfile($filepath);
                //exit;
            }else{
                echo "<h1>El archivo no existe.</h1>";
            }
        }
    }    
?>