<?php
session_start();
require_once "../controladores/control-presupuesto.controlador.php";
require_once "../modelos/control-presupuesto.modelo.php";

switch ($_GET["op"]){

	case 'guardarIngreso':
        if(isset($_POST["importeIngreso"])){
            $tabla="ingresos";

            $datos = array(
                "fecha_ingreso"         =>$_POST["fechaIngreso"],
                "concepto_ingreso"      =>strtoupper($_POST["conceptoIngreso"]),
                "descripcion_ingreso"   =>strtoupper($_POST["descripcionIngreso"]),
                "importe_ingreso"       =>$_POST["importeIngreso"],
                "ultusuario"            =>$_POST["idDeUsuario"],
                );

            $respuesta = ControladorPresupuesto::ctrIngreso($tabla, $datos);

            echo json_encode($respuesta);

        };
 	break;
        
}  //FIN DE SWITCH  $_POST["fechaIngreso"],
