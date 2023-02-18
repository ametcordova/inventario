<?php
date_default_timezone_set('America/Mexico_City');
require_once dirname( __DIR__ ).'/modelos/conexion.php';
class ModeloFacturaIngreso{

/*=============================================
	LISTAR SALIDAS
=============================================*/
static public function mdlListarFacturas($tabla, $fechadev1, $fechadev2, $usuario, $todes){
try {
 	//$where='1=1';
    //tabla=facturaingreso 
	$where='tb1.fechaelaboracion>="'.$fechadev1.'" AND tb1.fechaelaboracion<="'.$fechadev2.'" ';
    if($todes>0){
        $where.=' AND tb1.ultusuario="'.$usuario.'" ';
    }
	$where.=' ORDER BY tb1.id DESC';
  
	$sql="SELECT tb1.id, tb1.id_empresa, tb1.serie, tb1.folio, tb1.uuid, tb1.fechaelaboracion, tb1.fechatimbrado, tb1.fechacancelado, tb1.rfcemisor, tb1.idreceptor, tb1.idtipocomprobante, tb1.totalfactura, tb1.numcomppago, tb2.nombre AS nombrereceptor, tb2.rfc AS rfcreceptor
    FROM $tabla tb1 
    INNER JOIN clientes tb2 ON tb1.idreceptor=tb2.id
    WHERE ".$where;
  
	$stmt = Conexion::conectar()->prepare($sql);
  
	$stmt -> execute();
  
	return $stmt -> fetchAll();
  
    $stmt = null;

} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}

}	

/*=============================================
	OBTENER EL ULTIMO REGISTRO-ID CAPTURADO 
=============================================*/
static public function mdlObtenerUltimoNumero($tabla, $campo, $valor=null){
	try {
			
        if($valor!=null){
            $stmt=Conexion::conectar()->prepare("SELECT MAX($campo) AS folio FROM $tabla WHERE id=$valor");
        }else{
            $stmt=Conexion::conectar()->prepare("SELECT MAX(id) AS id, MAX($campo) AS folio FROM $tabla");
        }

        $stmt->execute();
        
        return $stmt->fetch();

		$stmt = null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}

}  

/*=============================================
	MOSTRAR DATOS DE RECEPTOR
=============================================*/
static public function mdlDatosReceptor($tabla, $campo, $valor){
	try{

            //$tabla='clientes';
    		$stmt = Conexion::conectar()->prepare("SELECT clie.curp, clie.email, clie.regimenfiscal, clie.formadepago, clie.id_usocfdi, clie.metodopago, rf.descripcion AS nombreregfiscal
            FROM $tabla clie
            INNER JOIN c_regimenfiscal rf ON clie.regimenfiscal=rf.id
            INNER JOIN c_usocfdi uso ON clie.id_usocfdi=uso.id
            WHERE clie.$campo = :$campo");

			$stmt -> bindParam(":".$campo, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

    		$stmt = null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}
	
}	


/*=============================================
	MOSTRAR USO DE CFDI
=============================================*/
static public function mdlMostrarUsoCFDI($tabla, $item, $valor, $aplica){
    try {
        $stmt = Conexion::conectar()->prepare("SELECT id, id_cfdi, descripcion, aplica_moral FROM $tabla WHERE aplica_moral=:aplica_moral");
        
        $stmt->bindParam(":aplica_moral", $aplica, PDO::PARAM_INT);

        $stmt -> execute();

        return $stmt -> fetchAll();

        $stmt = null;
    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }

}

/*=============================================
	BUSCAR 
=============================================*/
static public function mdlgetClavesfact($tabla, $campo, $valor){
    try {    

        $stmt = Conexion::conectar()->prepare("SELECT tb1.id, tb1.idprodservicio, tb1.concepto, tb1.objimpuesto, tb1.cantidad, tb1.preciounitario, tb1.unidadmedida, uni.nombre
        FROM $tabla tb1
        INNER JOIN c_claveunidad uni ON tb1.unidadmedida=uni.id_cfdi
        WHERE tb1.$campo LIKE '%".$valor."%' OR tb1.concepto LIKE '%".$valor."%' ");
        
        //$stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
        //$stmt->bindParam(":estado", $estado, PDO::PARAM_INT);

        $stmt -> execute();

        return $stmt -> fetchAll();

        $stmt = null;
    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }

}

/*=============================================
	GUARDAR FACTURA DE INGRESO
=============================================*/
static public function mdlCrearFacturaIngreso($tabla, $facturaingreso){
	try {      

        //OBTENEMOS EL ÚLTIMO ID GUARDADO EN TABLA FACTURAINGRESO
        $campo='folio';
        $query=self::mdlObtenerUltimoNumero($tabla, $campo);
            $folio=$query[1];
                if(is_null($folio)){
                  $folio=1;
                }else{
                    $folio++;
                }
        
        //GUARDAMOS EN TABLA FACTURAINGRESO
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_empresa, serie, folio, fechaelaboracion, rfcemisor, idregimenfiscalemisor, idtipocomprobante, idmoneda, idlugarexpedicion, idexportacion, idreceptor, idusocfdi, idformapago, idmetodopago, condicionesdepago, conceptos, observaciones, subtotal, tasaimpuesto, impuestos, totalfactura, saldoinsoluto, ultusuario) VALUES (:id_empresa, :serie, :folio, :fechaelaboracion, :rfcemisor, :idregimenfiscalemisor, :idtipocomprobante, :idmoneda, :idlugarexpedicion, :idexportacion, :idreceptor, :idusocfdi, :idformapago, :idmetodopago, :condicionesdepago, :conceptos, :observaciones, :subtotal,:tasaimpuesto, :impuestos, :totalfactura, :saldoinsoluto, :ultusuario)");

        $stmt->bindParam(":id_empresa", 	        $facturaingreso["id_empresa"], PDO::PARAM_INT);
        $stmt->bindParam(":serie", 	                $facturaingreso["serie"], PDO::PARAM_STR);
        $stmt->bindParam(":folio", 	                $folio, PDO::PARAM_INT);
        $stmt->bindParam(":fechaelaboracion",       $facturaingreso["fechaelaboracion"], PDO::PARAM_STR);
        $stmt->bindParam(":rfcemisor", 	            $facturaingreso["rfcemisor"], PDO::PARAM_STR);
        $stmt->bindParam(":idregimenfiscalemisor", 	$facturaingreso["idregimenfiscalemisor"], PDO::PARAM_STR);
        $stmt->bindParam(":idtipocomprobante", 	    $facturaingreso["idtipocomprobante"], PDO::PARAM_STR);
        $stmt->bindParam(":idmoneda", 	            $facturaingreso["idmoneda"], PDO::PARAM_STR);
        $stmt->bindParam(":idlugarexpedicion", 	    $facturaingreso["idlugarexpedicion"], PDO::PARAM_STR);
        $stmt->bindParam(":idexportacion", 	        $facturaingreso["idexportacion"], PDO::PARAM_STR);
        $stmt->bindParam(":idreceptor", 	        $facturaingreso["idreceptor"], PDO::PARAM_STR);
        $stmt->bindParam(":idusocfdi", 	            $facturaingreso["idusocfdi"], PDO::PARAM_STR);
        $stmt->bindParam(":idformapago", 	        $facturaingreso["idformapago"], PDO::PARAM_STR);
        $stmt->bindParam(":idmetodopago", 	        $facturaingreso["idmetodopago"], PDO::PARAM_STR);
        $stmt->bindParam(":condicionesdepago", 	    $facturaingreso["condicionesdepago"], PDO::PARAM_STR);
        $stmt->bindParam(":conceptos", 	            $facturaingreso["conceptos"], PDO::PARAM_STR);
        $stmt->bindParam(":observaciones",          $facturaingreso["observaciones"], PDO::PARAM_STR);
        $stmt->bindParam(":subtotal", 	            $facturaingreso["subtotal"], PDO::PARAM_STR);
        $stmt->bindParam(":tasaimpuesto", 	        $facturaingreso["tasaimpuesto"], PDO::PARAM_STR);
        $stmt->bindParam(":impuestos", 	            $facturaingreso["impuestos"], PDO::PARAM_STR);
        $stmt->bindParam(":totalfactura", 	        $facturaingreso["totalfactura"], PDO::PARAM_STR);
        $stmt->bindParam(":saldoinsoluto", 	        $facturaingreso["totalfactura"], PDO::PARAM_STR);
        $stmt->bindParam(":ultusuario",             $facturaingreso["ultusuario"], PDO::PARAM_INT);
        $stmt->execute();

        if($stmt){
           return "ok";
        }else{
            return "error";
        }

        $stmt=null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
   }

}

/*=============================================
	
=============================================*/
static public function mdlTimbrarFactura($tabla, $campo, $valor){
    try {    

        $stmt = Conexion::conectar()->prepare("SELECT tb1.*
        FROM $tabla tb1
        WHERE tb1.$campo= :$campo");
        
        $stmt->bindParam(":".$campo, $valor, PDO::PARAM_INT);

        $stmt -> execute();

        return $stmt -> fetch();

        $stmt = null;

    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }
}

/*=============================================
	BUSCAR 
=============================================*/
static public function mdlObtenerDatosFactura($tabla, $campo, $valor, $tipo){
    try {    
        if($tipo=="I"){
            //tabla=facturaingreso
            $stmt = Conexion::conectar()->prepare("SELECT tb1.*, emp.razonsocial AS nombreemisor, CONCAT(emp.direccion,' ',emp.num_ext,', ',emp.colonia,', ',emp.ciudad,', ',emp.estado) AS direccionemisor, emp.mailempresa, emp.telempresa, rf.descripcion AS regimenfiscalemisor, emp.numerocertificado, emp.codpostal, clie.nombre AS nombrereceptor, clie.rfc AS rfcreceptor, CONCAT(clie.direccion,' ',clie.num_int_ext,' ',clie.colonia,', ',clie.ciudad,', ',edo.nombreestado) AS direccionreceptor, clie.email, clie.telefono, clie.regimenfiscal, rfr.descripcion AS regimenfiscalreceptor, clie.codpostal AS codpostalreceptor, cfdi.id_cfdi, cfdi.descripcion AS usocfdi, fp.descripcionformapago, mp.descripcionmp, mn.id_moneda, mn.descripcion AS moneda, tc.descripcion AS descriptipocomprobante, ex.descripcion AS descripciontipoexporta
            FROM $tabla tb1
            INNER JOIN empresa emp ON emp.id=tb1.id_empresa
            INNER JOIN c_regimenfiscal rf ON rf.id=tb1.idregimenfiscalemisor
            INNER JOIN clientes clie ON clie.id=tb1.idreceptor
            INNER JOIN c_regimenfiscal rfr ON rfr.id=clie.regimenfiscal
            INNER JOIN c_usocfdi cfdi ON cfdi.id=tb1.idusocfdi
            INNER JOIN catestado edo ON edo.idestado=clie.estado
            INNER JOIN c_formapago fp ON fp.id=tb1.idformapago
            INNER JOIN c_metodopago mp ON mp.id_metodopago=tb1.idmetodopago
            INNER JOIN c_moneda mn ON mn.id=tb1.idmoneda
            INNER JOIN c_tiposdecomprobantes tc ON tc.idtipodecomprobante=tb1.idtipocomprobante
            INNER JOIN c_exportacion ex ON ex.id=tb1.idexportacion
            WHERE tb1.$campo= :$campo");
            
            $stmt->bindParam(":".$campo, $valor, PDO::PARAM_INT);

            $stmt -> execute();

            return $stmt -> fetch();

        }else{
            //tabla=complementodepago
            $stmt = Conexion::conectar()->prepare("SELECT tb1.*, emp.rfc AS rfcemisor, emp.razonsocial AS nombreemisor, CONCAT(emp.direccion,' ',emp.colonia,' ',emp.num_ext) AS direccionemisor, emp.mailempresa, emp.telempresa, emp.numerocertificado, emp.codpostal, clie.nombre AS nombrereceptor, clie.rfc AS rfcreceptor, CONCAT(clie.direccion,' ',clie.num_int_ext,' ',clie.colonia,', ',clie.ciudad,', ',edo.nombreestado) AS direccionreceptor, clie.email, clie.telefono, clie.regimenfiscal, clie.codpostal AS codpostalreceptor, cfdi.id_cfdi, cfdi.descripcion AS usocfdi, emp.regimenfiscalemisor, rf.descripcion AS descregimenfiscalemisor, rfr.descripcion AS regimenfiscalreceptor, fp.descripcionformapago, mp.id_metodopago, mp.descripcionmp, mn.id_moneda, mn.descripcion AS moneda, tc.descripcion AS descriptipocomprobante
            FROM complementodepago tb1 
            INNER JOIN empresa emp ON emp.id=tb1.idrfcemisor
            INNER JOIN clientes clie ON clie.id=tb1.idrfcreceptor
            INNER JOIN c_usocfdi cfdi ON cfdi.id=tb1.idusocfdi
            INNER JOIN c_regimenfiscal rf ON rf.id=emp.regimenfiscalemisor
            INNER JOIN c_regimenfiscal rfr ON rfr.id=clie.regimenfiscal
            INNER JOIN catestado edo ON edo.idestado=clie.estado
            INNER JOIN c_formapago fp ON fp.id=tb1.idformapagorep
            INNER JOIN c_metodopago mp ON mp.id=tb1.idmetodopagorep
            INNER JOIN c_moneda mn ON mn.id=tb1.idmoneda
            INNER JOIN c_tiposdecomprobantes tc ON tc.idtipodecomprobante=tb1.tipodecomprobante
            WHERE tb1.$campo= :$campo");
            //WHERE tb1.$campo= :$campo");
            
            $stmt->bindParam(":".$campo, $valor, PDO::PARAM_INT);

            $stmt -> execute();

            return $stmt -> fetch();

        }
        $stmt = null;
    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }
}

/*=============================================
OBTENER DATOS PARA ELABORAR COMPLEMENTO DE PAGO
=============================================*/
static public function mdlGetDatosFact($tabla, $campo, $valor){
    try {    
        $ids=implode(",",$valor);
        //tabla=facturaingreso
        $stmt = Conexion::conectar()->prepare("SELECT tb1.id, tb1.id_empresa, tb1.serie, tb1.folio, tb1.uuid, tb1.rfcemisor, tb1.idlugarexpedicion, emp.id AS idemisor, emp.serierep, emp.foliorep, tb1.subtotal, tb1.impuestos, tb1.totalfactura,tb1.saldoinsoluto, emp.razonsocial AS nombreemisor, cli.id AS idreceptor, cli.nombre AS nombrereceptor, cli.rfc AS rfcreceptor, tb1.idmoneda, mx.id_moneda, mx.descripcion AS moneda
        FROM $tabla tb1
        INNER JOIN empresa emp ON emp.id=tb1.id_empresa
        INNER JOIN clientes cli ON cli.id=tb1.idreceptor
        INNER JOIN c_moneda mx ON mx.id=tb1.idmoneda
        WHERE tb1.id IN ($ids)");

        //WHERE tb1.$campo= :$campo");
        //$stmt->bindParam(":id", $valor, PDO::PARAM_INT);

        $stmt -> execute();

        return $stmt -> fetchAll();

        $stmt = null;

    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }
}

/*=============================================
	BUSCAR 
=============================================*/
static public function mdlObtenerDatosTimbre($tabla, $campo, $valor){
    try {    
        //tabla=datosfacturatimbre
        $stmt = Conexion::conectar()->prepare("SELECT tb2.* FROM $tabla tb2
        WHERE tb2.$campo= :$campo");
        
        $stmt->bindParam(":".$campo, $valor, PDO::PARAM_INT);

        $stmt -> execute();

        return $stmt -> fetch();

        $stmt = null;

    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }
}

/*=============================================
MOSTRAR EMPRESA
=============================================*/
static public function mdlGetDatosEmpresa($tabla, $item, $valor){
	try{

        if($item=='status'){
            $stmt = Conexion::conectar()->prepare("SELECT id, rfc, razonsocial FROM $tabla WHERE $item=:$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_INT);
            $stmt -> execute();
            return $stmt -> fetchAll();
        }else{
            $stmt = Conexion::conectar()->prepare("SELECT id, rfc, razonsocial, iva, regimenfiscalemisor, codpostal, id_exportacion, seriefacturacion FROM $tabla WHERE $item=:$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_INT);
            $stmt -> execute();
            return $stmt -> fetch();
        }
	
	}catch(Exception $e) {
		return $e->getMessage() ;
	}

	$stmt = null;
	
}

/*=============================================
TABLA FORMA PAGO
=============================================*/
static public function mdlGetFormaPago($tabla){
    try {    
        //tabla=c_formapago
        $stmt = Conexion::conectar()->prepare("SELECT id, descripcionformapago FROM $tabla");
        $stmt -> execute();
        return $stmt -> fetchAll();
        $stmt = null;

    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }
}

/*=============================================
TABLA OBJETO DE IMPUESTO
=============================================*/
static public function mdlGetObjetoImpuesto($tabla){
    try {    
        //tabla=c_objetoimp
        $stmt = Conexion::conectar()->prepare("SELECT id, descripcion FROM $tabla");
        $stmt -> execute();
        return $stmt -> fetchAll();
        $stmt = null;

    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }
}

/*=============================================
TABLA OBJETO DE IMPUESTO
=============================================*/
static public function mdlGetTasaImpuesto($tabla){
    try {    
        //tabla=c_objetoimp
        $stmt = Conexion::conectar()->prepare("SELECT id, descripcion, tasa FROM $tabla");
        $stmt -> execute();
        return $stmt -> fetchAll();
        $stmt = null;

    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }
}

/*=============================================
	GUARDAR FACTURA DE COMPLEMENTO DE PAGO
=============================================*/
static public function mdlGuardarRep($tabla, $complementodepago){
	try {      
        //OBTENEMOS EL ÚLTIMO ID GUARDADO EN TABLA 
        $campo='foliorep';
        $valor=$complementodepago["idrfcemisor"];
        $tablaempresa='empresa';
        $query=self::mdlObtenerUltimoNumero($tablaempresa, $campo, $valor);
            $folio=$query[0];
                if(!is_null($folio)){
                    $folio++;
                    $foliorep='P'.$folio;
                }else{
                    $folio=1;
                    $foliorep='P'.$folio;
                }
        
        //GUARDAMOS EN TABLA FACTURAINGRESO
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(tipodecomprobante, foliorep, fechaelaboracion, idrfcemisor,cpemisor, idrfcreceptor, fechapago, idformapagorep, idmetodopagorep, idusocfdi, idobjetoimpuesto, idmoneda, numoperacion, cuentaordenante, cuentabeneficiario, conceptos, doctosrelacionados, idimpuesto, totalrecibo, tasa, subtotal, totalimpuesto, saldoinsoluto, ultusuario) VALUES (:tipodecomprobante, :foliorep, :fechaelaboracion, :idrfcemisor,:cpemisor, :idrfcreceptor, :fechapago, :idformapagorep, :idmetodopagorep, :idusocfdi, :idobjetoimpuesto, :idmoneda, :numoperacion, :cuentaordenante, :cuentabeneficiario, :conceptos, :doctosrelacionados, :idimpuesto, :totalrecibo, :tasa, :subtotal, :totalimpuesto, :saldoinsoluto, :ultusuario)");

        $stmt->bindParam(":tipodecomprobante",  $complementodepago["tipodecomprobante"], PDO::PARAM_STR);
        $stmt->bindParam(":foliorep",           $foliorep, PDO::PARAM_STR);
        $stmt->bindParam(":fechaelaboracion",   $complementodepago["fechaelaboracion"], PDO::PARAM_STR);
        $stmt->bindParam(":idrfcemisor",        $complementodepago["idrfcemisor"], PDO::PARAM_INT);
        $stmt->bindParam(":cpemisor",           $complementodepago["cpemisor"], PDO::PARAM_INT);
        $stmt->bindParam(":idrfcreceptor",      $complementodepago["idrfcreceptor"], PDO::PARAM_INT);
        $stmt->bindParam(":fechapago",          $complementodepago["fechapago"], PDO::PARAM_STR);
        $stmt->bindParam(":idformapagorep",     $complementodepago["idformapagorep"], PDO::PARAM_INT);
        $stmt->bindParam(":idmetodopagorep",    $complementodepago["idmetodopagorep"], PDO::PARAM_INT);
        $stmt->bindParam(":idusocfdi",          $complementodepago["idusocfdi"], PDO::PARAM_INT);
        $stmt->bindParam(":idobjetoimpuesto",   $complementodepago["idobjetoimpuesto"], PDO::PARAM_INT);
        $stmt->bindParam(":idmoneda",           $complementodepago["idmoneda"], PDO::PARAM_INT);
        $stmt->bindParam(":numoperacion",       $complementodepago["numoperacion"], PDO::PARAM_STR);
        $stmt->bindParam(":cuentaordenante",    $complementodepago["cuentaordenante"], PDO::PARAM_STR);
        $stmt->bindParam(":cuentabeneficiario", $complementodepago["cuentabeneficiario"], PDO::PARAM_STR);
        $stmt->bindParam(":conceptos",          $complementodepago["conceptos"], PDO::PARAM_STR);
        $stmt->bindParam(":doctosrelacionados", $complementodepago["doctosrelacionados"], PDO::PARAM_STR);
        $stmt->bindParam(":idimpuesto",         $complementodepago["idimpuesto"], PDO::PARAM_INT);
        $stmt->bindParam(":totalrecibo",        $complementodepago["totalrecibo"], PDO::PARAM_STR);
        $stmt->bindParam(":tasa",               $complementodepago["tasa"], PDO::PARAM_STR);
        $stmt->bindParam(":subtotal",           $complementodepago["subtotal"], PDO::PARAM_STR);
        $stmt->bindParam(":totalimpuesto",      $complementodepago["totalimpuesto"], PDO::PARAM_STR);
        $stmt->bindParam(":saldoinsoluto",      $complementodepago["saldoinsoluto"], PDO::PARAM_STR);
        $stmt->bindParam(":ultusuario",         $complementodepago["ultusuario"], PDO::PARAM_INT);
        $stmt->execute();

        if($stmt){
           //ACTUALIZA FOLIO DE COMPLEMENTO DE PAGO TABLA EMPRESA
            $query = Conexion::conectar()->prepare("UPDATE $tablaempresa SET foliorep=:foliorep WHERE id = :id");
            $query->bindParam(":id",        $complementodepago["idrfcemisor"], PDO::PARAM_INT);
            $query->bindParam(":foliorep",  $folio, PDO::PARAM_INT);
            $query->execute();

            if($query){
                //ACTUALIZA FOLIO DE COMPLEMENTO DE PAGO EN TABLA FACTURAS. * CAMBIARLO A TIMBRARREP20.PHP PARA ACTUALIZAR AL TIMBRAR REP *
                $status=1;
                $datos_json=json_decode($complementodepago["doctosrelacionados"],TRUE);		//decodifica los datos JSON 

                foreach($datos_json as $valor) {
                    $serie=$valor['Serie'];
                    $folio=$valor['Folio'];

                    $query = Conexion::conectar()->prepare("UPDATE facturas SET numcomplemento=:numcomplemento, fechapagado=:fechapagado, status=:status WHERE serie=:serie AND numfact=:numfact");
                    $query->bindParam(":serie",             $serie, PDO::PARAM_STR);
                    $query->bindParam(":numfact",           $folio, PDO::PARAM_STR);
                    $query->bindParam(":numcomplemento",    $foliorep, PDO::PARAM_STR);
                    $query->bindParam(":fechapagado",       $complementodepago["fechapago"], PDO::PARAM_STR);
                    $query->bindParam(":status",            $status, PDO::PARAM_INT);
                    $query->execute();
                }
                
            }

           return "ok";


        }else{
            return "error";
        }

        $stmt=null;
        $query=null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
   }
}

/*=============================================
	LISTAR SALIDAS
=============================================*/
static public function mdlListarRep20($tblRep20, $year, $usuario, $todes){
    try {
         $where='1=1';
              
        //$where='tbl.fechaelaboracion="'.$year.'" ';
        if($todes>0){
            $where.=' AND tbl.ultusuario="'.$usuario.'"';
        }
        $where.=' ORDER BY tbl.id DESC';
      
        $sql="SELECT tbl.id, tbl.foliorep, tbl.fechaelaboracion, tbl.fechatimbradorep, tbl.fechapago, tbl.totalrecibo, emp.rfc AS rfcemisor, cli.rfc AS rfcreceptor FROM $tblRep20 tbl
        INNER JOIN empresa emp ON emp.id=tbl.idrfcemisor
        INNER JOIN clientes cli ON cli.id=tbl.idrfcreceptor
        WHERE ".$where;
        
              $stmt = Conexion::conectar()->prepare($sql);
      
        $stmt -> execute();
      
        return $stmt -> fetchAll();
      
        $stmt = null;
    
    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }
    
}	
    
/*=========================================================================================*/   






} //fin de la clase
