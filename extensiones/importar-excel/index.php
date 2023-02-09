<?php
use Baulphp\DBClass;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

require_once 'DBClass.php';
$db = new DBClass();
$conexion = $db->getConnection();
require_once ('../vendor/autoload.php');

if (isset($_POST["importar"])) {

    $allowedFileType = [
        'application/vnd.ms-excel',
        'text/xls',
        'text/xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    if (in_array($_FILES["file"]["type"], $allowedFileType)) {

        $targetPath = 'subidas/' . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

        $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadSheet = $Reader->load($targetPath);
        $excelSheet = $spreadSheet->getActiveSheet();
        $spreadSheetAry = $excelSheet->toArray();
        $sheetCount = count($spreadSheetAry);

        for ($i = 0; $i <= $sheetCount; $i ++) {
            $producto = "";
            if (isset($spreadSheetAry[$i][0])) {
                $producto = mysqli_real_escape_string($conexion, $spreadSheetAry[$i][0]);
            }
            $descripcion = "";
            if (isset($spreadSheetAry[$i][1])) {
                $descripcion = mysqli_real_escape_string($conexion, $spreadSheetAry[$i][1]);
            }

            $os = "";
            if (isset($spreadSheetAry[$i][2])) {
                $os = mysqli_real_escape_string($conexion, $spreadSheetAry[$i][2]);
            }

            if (!empty($producto) || !empty($descripcion)) {
                $query = "insert into tbl_productos(producto,descripcion, os) values(?,?,?)";
                $paramType = "sss";
                $paramArray = array(
                    $producto,
                    $descripcion,
                    $os
                );

                //GUARDA EN LA TABLA tbl_productos
                $insertId = $db->insert($query, $paramType, $paramArray);

                if (! empty($insertId)) {
                    $type = "success";
                    $message = "Datos de Excel importados a la base de datos";
                } else {
                    $type = "danger";
                    $message = "Problema al importar datos de Excel";
                }
            }
        }
    } else {
        $type = "danger";
        $message = "Tipo de archivo invalido. Cargar archivo de Excel.";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="Baulphp and Bootstrap contributors">
<title>Baulphp.com - Importar archivo Excel a MySQL con PHPSpreadsheet</title>

<!-- Bootstrap core CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
<style>
#respuesta {
	padding: 10px;
	margin-top: 10px;
	border-radius: 2px;
	display: none;
}
div#respuesta.display-block {
	display: block;
}
</style>
</head>
<body>
<main>
  <div class="container py-4">
    <header class="pb-3 mb-4 border-bottom"> <a href="/" class="d-flex align-items-center text-dark text-decoration-none"> <span class="fs-4">Importar archivo Excel a MySQL con PHPSpreadsheet</span> </a> </header>
    <div class="row align-items-md-stretch">
      <div class="col-md-6">
        <form class="row g-3" action="" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
          <div class="col-md-9">
            <input class="form-control form-control-sm" id="file" name="file" type="file" accept=".xls,.xlsx">
          </div>
          <div class="col-md-3">
            <button type="submit" id="submit" name="importar" class="btn btn-primary btn-sm mb-3">Importar</button>
          </div>
        </form>
      </div>
    </div>
    <div class="row align-items-md-stretch">
      <div class="col-md-6">
        <div id="respuesta" class="alert alert-<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
          <?php if(!empty($message)) { echo $message; } ?>
        </div>
      </div>
    </div>
    <div class="row align-items-md-stretch">
      <div class="col-md-12">
        <?php
$sqlSelect = "SELECT * FROM tbl_productos";
$result = $db->select($sqlSelect);
if (! empty($result)) {
    ?>
        <table  class="table table-sm table-striped">
          <thead>
            <tr>
              <th scope="col">Producto</th>
              <th scope="col">Descripción</th>
              <th scope="col">OS</th>
            </tr>
          </thead>
          <tbody>
            <?php
    foreach ($result as $row) {
        ?>
            <tr>
              <td scope="row"><?php  echo $row['producto']; ?></td>
              <td><?php  echo $row['descripcion']; ?></td>
              <td><?php  echo $row['os']; ?></td>
            </tr>
            <?php
    }
    ?>
          </tbody>
        </table>
        <?php
}
?>
      </div>
    </div>
    
    <!--End contenidos-->
    <footer class="pt-3 mt-4 text-muted border-top"> &copy; <?=date("Y");?> </footer>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</body>
</html>