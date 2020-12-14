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
function fechaHoraMexico($timestamp) {
  date_default_timezone_set('UTC');
  date_default_timezone_set("America/Mexico_City");
  $hora = strftime("%I:%M:%S %p", strtotime($timestamp)); //Descomentar en caso de requerir hora
  setlocale(LC_TIME, 'spanish');
  $fecha = utf8_encode(strftime("%A %d de %B del %Y", strtotime($timestamp)));
  return ($fecha.' '.$hora);//$hora; concatenar con fecha para obtener fecha y hora
}
