<?php
session_start();
require_once "../controladores/clientes.controlador.php";
require_once "../modelos/clientes.modelo.php";

switch ($_GET["op"]){

	case 'mostrarClie':

            $item = null;
            $valor = trim(strip_tags($_POST['searchTerm']));
            //$search = $_POST['searchTerm'];

            $respuesta = ControladorClientes::ctrMostrarClientes($item, $valor);

            $json []= ['id'=>['0'], 'text'=>['Ninguno']];
            foreach($respuesta as $key=>$value){
                $json[] = ['id'=>$value['id'], 'text'=>$value['nombre']];
             }   

            echo json_encode($json);

 	break;
        
     case 'listarVentas': 
        require_once "../controladores/reportedeventas.controlador.php";
        require_once "../modelos/reportedeventas.modelo.php";
        
        //TRAEMOS LA INFORMACIÓN 
        $tabla=$_GET["idNumAlma"];
        $campoFam = "id_familia";
        $claveFam = isset($_GET["idNumFam"])?$_GET["idNumFam"]:null;
        $idNumCaja = $_GET["idNumCaja"];
        $idNumCliente = $_GET["idNumCliente"];
        $idNumProds = isset($_GET["idNumProds"])?$_GET["idNumProds"]:null;
        $idTipoMovs = isset($_GET["idTipoMovs"])?$_GET["idTipoMovs"]:null;
        $fechaVta1 = $_GET["idFechVta1"];
        $fechaVta2 = $_GET["idFechVta2"];

        //TRAER LOS DATOS DEL ALMACEN SELECCIONADO
        $respuesta = ControladorVentas::ctrListarVentas($tabla, $campoFam, $claveFam, $fechaVta1, $fechaVta2, $idNumCaja, $idNumCliente, $idNumProds, $idTipoMovs);

        //var_dump($respuesta);

        $data= Array();
        foreach ($respuesta as $key => $value) {
                //$fechaEntro = date('d-m-Y', strtotime($value["fechaentrada"]));
            
            //$resurtir = ($value["surtir"]<0)? "<button class='btn btn-danger btn-sm'>".$value["surtir"]."</button>" : "<button class='btn btn-success btn-sm'>".$value["surtir"]."</button>";
            $sumaconpromo=0;
            $preciodecosto=number_format($value['precio_compra'],2,".",",");
            $totcosto=number_format($value['precio_compra']*$value['cant'],2,".",",");
            $sumasinpromo=number_format($value["sinpromo"],2,".",",");
            if($value["promo"]>0){
                $sumaconpromo="$".number_format($value["promo"],2,".",",");
            }
            $sumatotal=number_format($value['promo']+$value['sinpromo'],2,".",",");
            $dif=number_format(($value['promo']+$value['sinpromo'])-($value['precio_compra']*$value['cant']),2,".",",");
            $data[]=array(
                 "0"=>'<td style="width:10px;">'.$value["id_familia"].'</td>',
                 "1"=>'<td style="width:10px;">'.$value["id_categoria"].'</td>',
                 "2"=>'<td style="width:95px;">'.$value["codigointerno"].'</td>',
                 "3"=>'<td style="width:320px;">'.$value["descripcion"].'</td>',
                 "4"=>$value["cant"],
                 "5"=>$preciodecosto,
                 "6"=>$totcosto,	
                 "7"=>$sumasinpromo,
                 "8"=>$sumaconpromo,
                 "9"=>$sumatotal,
                 "10"=>$dif,
            );        
        }
        
             $results = array(
                 "sEcho"=>1, //Información para el datatables
                 "iTotalRecords"=>count($data), //enviamos el total registros al datatable
                 "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
                 "aaData"=>$data);


        echo json_encode($results);




     break;
        
}  //FIN DE SWITCH
