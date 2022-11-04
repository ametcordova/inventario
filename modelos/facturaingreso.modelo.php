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
  
	$sql="SELECT tb1.*,tb2.nombre AS nombrereceptor
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
static public function mdlObtenerUltimoNumero($tabla, $campo){
	try {
			
        if($campo==null){
            $stmt=Conexion::conectar()->prepare("SELECT MAX(id) AS id, MAX(folio) AS folio FROM $tabla");
        }else{
            $stmt=Conexion::conectar()->prepare("SELECT MAX($campo) AS folio FROM $tabla");
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
    		$stmt = Conexion::conectar()->prepare("SELECT clie.curp, clie.email, clie.regimenfiscal, clie.formadepago, rf.descripcion AS nombreregfiscal 
            FROM $tabla clie
            INNER JOIN c_regimenfiscal rf ON clie.regimenfiscal=rf.id
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
            $folio=$query[0];
                if(is_null($folio)){
                  $folio=1;
                }else{
                    $folio++;
                }
        
        //GUARDAMOS EN TABLA FACTURAINGRESO
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_empresa, serie, folio, fechaelaboracion, rfcemisor, idregimenfiscalemisor, idtipocomprobante, idmoneda, idlugarexpedicion, idexportacion, idreceptor, idusocfdi, idformapago, idmetodopago, conceptos, observaciones, subtotal, tasaimpuesto, impuestos, totalfactura, ultusuario) VALUES (:id_empresa, :serie, :folio, :fechaelaboracion, :rfcemisor, :idregimenfiscalemisor, :idtipocomprobante, :idmoneda, :idlugarexpedicion, :idexportacion, :idreceptor, :idusocfdi, :idformapago, :idmetodopago, :conceptos, :observaciones, :subtotal,:tasaimpuesto, :impuestos, :totalfactura, :ultusuario)");

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
        $stmt->bindParam(":conceptos", 	            $facturaingreso["conceptos"], PDO::PARAM_STR);
        $stmt->bindParam(":observaciones",          $facturaingreso["observaciones"], PDO::PARAM_STR);
        $stmt->bindParam(":subtotal", 	            $facturaingreso["subtotal"], PDO::PARAM_STR);
        $stmt->bindParam(":tasaimpuesto", 	        $facturaingreso["tasaimpuesto"], PDO::PARAM_STR);
        $stmt->bindParam(":impuestos", 	            $facturaingreso["impuestos"], PDO::PARAM_STR);
        $stmt->bindParam(":totalfactura", 	        $facturaingreso["totalfactura"], PDO::PARAM_STR);
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
	BUSCAR 
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
static public function mdlObtenerDatosFactura($tabla, $campo, $valor){
    try {    
        //tabla=facturaingreso
        $stmt = Conexion::conectar()->prepare("SELECT tb1.*, emp.razonsocial AS nombreemisor, CONCAT(emp.direccion,' ',emp.colonia,' ',emp.num_ext) AS direccionemisor, emp.mailempresa, emp.telempresa, rf.descripcion AS regimenfiscalemisor, emp.numerocertificado, emp.codpostal, clie.nombre AS nombrereceptor, clie.rfc AS rfcreceptor, CONCAT(clie.direccion,' ',clie.num_int_ext,' ',clie.colonia,', ',clie.ciudad,', ',edo.nombreestado) AS direccionreceptor, clie.email, clie.telefono, clie.regimenfiscal, rfr.descripcion AS regimenfiscalreceptor, clie.codpostal AS codpostalreceptor, cfdi.id_cfdi, cfdi.descripcion AS usocfdi, fp.descripcionformapago, mp.descripcionmp, mn.id_moneda, mn.descripcion AS moneda, tc.descripcion AS descriptipocomprobante, ex.descripcion AS descripciontipoexporta
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


} //fin de la clase


?>
