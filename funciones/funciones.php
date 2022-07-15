<?php
/******************************************************
     * Función para verificar si tiene los permisos
******************************************************/
 function getAccess($bit1, $bit2){
  return (((int)$bit1 & (int)$bit2) == 0) ? false : true;     //hay que notar que estamos usando el símbolo & (ampersand). Este es el operador AND a nivel de bit en PHP; el operador AND booleano se representa con dos símbolos ampersand &&
}

/********************************************************
* Función para traer los permisos segun usuario y modulo
********************************************************/
function accesomodulo($tabla, $usuario, $module, $campo){
  $acceso=0;
  $permiso = ControladorPermisos::ctrGetAccesos($tabla, $usuario, $module, $campo);
  $acceso=$permiso[0];
    
  return $acceso;

}

//FUNCTION PARA MOSTRAR LA FECHA EN ESPAÑOL Y DE MEXICO
function fechaHoraMexico() {
  date_default_timezone_set('UTC');
  date_default_timezone_set("America/Mexico_City");
  $fechahoy = date('d-m-Y h:i:s a', time());
  return $fechahoy;   //$hora; concatenar con fecha para obtener fecha y hora
}

//FUNCION PARA SACAR EL PESO DE UN ARCHIVO
function formatBytes($bytes, $precision = 2) {
  $unit = ["B", "KB", "MB", "GB"];
  $exp = floor(log($bytes, 1024)) | 0;
  return round($bytes / (pow(1024, $exp)), $precision).$unit[$exp];
  //echo formatBytes('1876144', 2);
}

/********************************************************************************* */
function minutosTranscurridos($fecha_i,$fecha_f){
  $minutos = (strtotime($fecha_i)-strtotime($fecha_f))/60;
  $minutos = abs($minutos); $minutos = floor($minutos);
  return $minutos;
}
/********************************************************************************* */

/********************************************************************************* */
/*                                                                              */
/********************************************************************************* */
function checkstring($stringacheck){
  $sane = $stringsanitizado = $sane_char ="";
  if (!preg_match('/^[$\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $stringacheck)) {
      return;
  }

  // Caracteres prohibidos porque en algunos sistemas operativos no son admitidos como nombre de fichero
  // Obtenida del código fuente de WordPress.org  SI SE ACEPTA '/'
  $forbidden_chars = array(
      "?", "[", "]", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&",
      "$", "#", "*", "(", ")" , "|", "~", "`", "!", "{", "}", "%", "+" , chr(0));

  // Caracteres que queremos reemplazar por otros que hacen el texto igualmente legible    
  $replace_chars = array(
      'áéíóúäëïöüàèìòùñ',
      'aeiouaeiouaeioun'
  );

  // Convertimos el texto a analizar a minúsculas
  $source = strtoupper($stringacheck);

  //Comprobamos cada carácterIdit1611
  for( $i=0 ; $i < strlen($source) ; $i++ ) {
      $sane_char = $source_char = $source[$i];
      
      if ( in_array( $source_char, $forbidden_chars ) ) {
          $sane_char = "_";
          $sane .= $sane_char;
          continue;
      }

      $pos = strpos( $replace_chars[0], $source_char);
      if ( $pos !== false ) {
          $sane_char = $replace_chars[1][$pos];
          $sane .= $sane_char;
          continue;
      }

      if ( ord($source_char) < 32 || ord($source_char) > 128 ) {
          /* Todos los caracteres codificados por debajo de 32 o encima de 128 son especiales Ver http://www.asciitable.com/ */
          $sane_char = "_";
          $sane .= $sane_char;
          continue;
      }
      // Si ha pasado todos los controles, aceptamos el carácter
      $stringsanitizado .= $sane_char;
  }

  return $stringsanitizado;
}
/********************************************************************************* */
function getHttpStatusMessage($statusCode){
  $httpStatus = array (
  100 => 'Continue',
  101 => 'Switching Protocols',
  200 => 'OK',
  201 => 'Created',
  202 => 'Accepted',
  203 => 'Non-Authoritative Information ',
  204 => 'No Content',
  205 => 'Reset Content',
  206 => 'Partial Content',
  300 => 'Multiple Choices',
  301 => 'Moved Permanently',
  302 => 'Found',
  303 => 'See Other',
  304 => 'Not Modified',
  305 => 'Use Proxy',
  306 => '(Unused)' ,
  307 => 'Temporary Redirect',
  400 => 'Bad Request',
  401 => 'Unauthorized',
  402 => 'Payment Required',
  403 => 'Forbidden',
  404 => 'Not Found',
  405 => 'Method Not Allowed' ,
  406 => 'Not Acceptable',
  407 => 'Proxy Authentication Required' ,
  408 => 'Request Timeout',
  409 => 'Conflict',
  410 => 'Gone',
  411 => 'Length Required',
  412 => 'Precondition Failed',
  413 => 'Request Entity Too Large ',
  414 => 'Request-URI Too Long',
  415 => 'Unsupported Media Type' ,
  416 => 'Requested Range Not Satisfiable ',
  417 => 'Expectation Failed',
  500 => 'Internal Server Error' ,
  501 => 'Not Implemented',
  502 => 'Bad Gateway',
  503 => 'Service Unavailable',
  504 => 'Gateway Timeout',
  505 => 'HTTP Version Not Supported ');
  return ($httpStatus[$statusCode])?$httpStatus[$statusCode]:$status=[500];
  }
  
  /*************************************************************************** */
function verURL(){
  $myurl="https://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
  $ip = (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    return $myurl." - IP: ".$ip;
}


// SELECT ctah.`nombrecuentahab`,ctah.`id_destino`, dest.nombre, ctah.`numerotarjeta`,ctah.`usodeposito`,ctah.`fechapago`, EXTRACT(DAY FROM ctah.fechapago) AS dia, EXTRACT(DAY FROM CURDATE()) as ahora 
// FROM `cuentahabientes` ctah
// INNER JOIN destinatarios dest ON dest.id=ctah.id_destino
// WHERE ctah.`notificacion`>0
?>

