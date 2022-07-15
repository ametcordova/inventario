<!DOCTYPE html>
<html lang="en">
<style>
  #centrarDIV {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
  <script defer src="./vistas/js/dash.js"></script>
</html>
<?php
date_default_timezone_set('America/Mexico_City');
require_once "modelos/conexion.php";
?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1>Tablero
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
              <li class="breadcrumb-item active">Tablero</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <?php
        
        $sql = "SELECT ctah.id, ctah.`nombrecuentahab`,ctah.`id_destino`, dest.nombre, ctah.`numerotarjeta`,ctah.`usodeposito`, date_format(ctah.`fechapago`, '%d-%m-%Y') as fecha_pago, EXTRACT(DAY FROM ctah.fechapago) AS dia, EXTRACT(DAY FROM CURDATE()) as ahora, date_format(ctah.ultmodificacion, '%d-%m-%Y') as fecha_deposito 
        FROM `cuentahabientes` ctah
        INNER JOIN destinatarios dest ON dest.id=ctah.id_destino
        WHERE ctah.`notificacion`>0 ORDER BY ctah.fechapago ASC";

        $query = Conexion::conectar()->prepare($sql);
        $query->execute();

        $resultado = $query->fetchAll();
        
        //print_r($resultado);
        ?>

        <div class="row ml-1 table-responsive" style="background-color:ghostwhite;">
          <table class="table table-sm table-bordered compact table-hover table-striped" id="depot">
            <thead class="thead-dark">
              <tr style="font-size:0.80rem;" class="text-center">
                <th scope="col">Nombre cliente</th>
                <th scope="col">Concepto</th>
                <th scope="col">Numero de Tarjeta</th>
                <th scope="col">Nombre de Tarjeta</th>
                <th scope="col">Fecha Pago</th>
                <th scope="col">Fecha Dep.</th>
                <th scope="col" class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody style="font-size:0.80rem;">

            <?php
                foreach($resultado as $row) {
                  echo '<tr>
                    <td>'.$row["nombrecuentahab"].'</th>
                    <td>'.$row['usodeposito'].'</td>
                    <td>'.$row['numerotarjeta'].'</td>
                    <td>'.$row['nombre'].'</td>
                    <td class="text-center">'.$row['fecha_pago'].'</td>
                    <td class="text-center">'.$row['fecha_deposito'].'</td>
                    <td class="btn-group btn-group-sm font-weight-bold" role="group" aria-label="Basic example" id="centrarDIV">
                    <button type="button" class="btn btn-warning change" data-stat="'.$row['id'].'">Por pagar</button>
                    <button type="button" class="btn btn-danger">Quitar</button>
                    </td>
                  </tr>';

                  //if($row['dia']>$row['ahora']){
                      // echo '<div class="label">
                      //   <p>Pago a '.$row['nombrecuentahab'].' por concepto de: '.$row['usodeposito'].' numero de Referencia: '.$row['numerotarjeta'].' de: '.$row['nombre'].'. Fecha de Pago: '.$row['fechapago'].'
                      //   </p>
                      // </div>';
                  //}
              } 
            ?>   

            </tr>
            </tbody>
          </table>


        </div>

        <?php

        ?>

   
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper 


-->
  