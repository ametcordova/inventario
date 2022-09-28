
<!-- Main Footer -->
  <footer class="main-footer p-0 m-0">

    <div class="col-md-12 d-flex">
    
        <p class="col-md-5 justify-content-end">
          <!-- Default to the left -->
          <strong>Copyright &copy; 2019 - <?= date("Y");?> <a href="index.php">AdminInV</a>.</strong> Todos Los Derechos Reservados.
        </p>

        <p class="col-md-5 text-white-50">
          <?php
            include_once "funciones/funciones.php";
            echo verURL()
          ?>
        </p>

      <p class="col-md-2 float-right">
        <!-- To the right -->
        <strong style="color:red">@</strong>Kordova - (961)-248-0768
      </p>
        

    </div>
    
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="extensiones/plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap 4 SE QUITO POR QUE NO SERVIA-->

<script src="extensiones/plugins/jquery-ui-1.12.1/jquery-ui.js"></script>

<!-- AdminLTE App -->
<script src="extensiones/dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
 <script src="extensiones/dist/js/demo.js"></script>
<!-- <script src="extensiones/dist/js/pages/dashboard3.js"></script>  -->

<!-- iCheck -->
<script src="extensiones/plugins/iCheck/icheck.min.js"></script>

<!-- DataTables -->
<script type="text/javascript" src="extensiones/datatables/datatables.min.js?v=01012021"></script>
<!-- funcion para Datatables para ordener por fecha-->
<script type="text/javascript" src="extensiones/datatables/date-eu.js?v=01012021"></script> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>

<!-- DataTables -->
<!-- <script src="extensiones/plugins/datatables/jquery.dataTables.js"></script>
<script src="extensiones/plugins/datatables/dataTables.bootstrap4.js"></script>

<script src="extensiones/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="extensiones/plugins/datatables/buttons.print.min.js"></script>

<script src="extensiones/plugins/datatables/ext/jszip.min.js"></script>
<script src="extensiones/plugins/datatables/ext/pdfmake.min.js"></script>
<script src="extensiones/plugins/datatables/ext/vfs_fonts.js"></script>
<script src="extensiones/plugins/datatables/ext/buttons.html5.min.js"></script>

<script src="extensiones/plugins/datatables/extensions/Responsive/js/dataTables.Responsive.js"></script> -->
<!--
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.4/js/buttons.html5.min.js"></script>
-->
<!--<script src="extensiones/plugins/sweetalert/sweetalert.min.js"></script> -->

<!-- InputMask -->
<script src="extensiones/plugins/input-mask/jquery.inputmask.js"></script>
<script src="extensiones/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="extensiones/plugins/input-mask/jquery.inputmask.extensions.js"></script>

<script src="extensiones/plugins/jqueryNumber/jquery.number.js"></script>

<script src="extensiones/plugins/moment.js"></script>


<script src="extensiones/plugins/datepicker/bootstrap-datepicker.js"></script>

<!-- daterangepicker -->
<script src="extensiones/plugins/daterangepicker/daterangepicker.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script src="extensiones/plugins/tempusdominus-bootstrap4/tempusdominus-bootstrap-4.min.js"></script>

<!-- Select2 -->
<script src="extensiones/plugins/select2/select2.full.min.js"></script>

<!-- axios -->
<script src="extensiones/axios-0.19.2/axios.min.js"></script>
<!-- notie -->
<script src="https://unpkg.com/notie"></script>
<!-- Alertas ohSnap -->
<script src="extensiones/plugins/ohSnap/ohsnap.js"></script>

<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/rxjs/4.1.0/rx.all.js"></script> -->


</body>
</html>