<?php
require_once dirname( __DIR__ ).'/config/accesos.php';
class Conexion{

	static public function conectar(){

		$link = new PDO("mysql:host=localhost;dbname=inventario","root","");
		//$link = new PDO("mysql:host=localhost;dbname=fipabide_nunosco","fipabide_nunosco","Conexion$1998");

		$link->exec("set names utf8");

		return $link;

	}

}