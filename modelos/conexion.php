<?php
date_default_timezone_set('America/Mexico_City');
require_once dirname( __DIR__ ).'/config/parametros.php';
require_once dirname( __DIR__ ).'/config/accesos.php';
class Conexion{
	//protected $link;

	//protected function conectar(){
	static function conectar(){
		try {
			$link = new PDO("mysql:host=localhost;dbname=inventario","root","");
			//$link = new PDO("mysql:host=localhost;dbname=fipabide_nunosco","fipabide_nunosco","Conexion$1998");

			$link->exec("set names utf8");

			$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $link;

		} catch (PDOException $e){
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}

	}

}