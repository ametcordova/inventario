<script src="extensiones/plugins/moment.js"></script>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header m-1 p-1">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1>
                <small>Panel de Control</small>
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Tablero</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
		        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarMedidas"><i class="fa fa-plus-circle"></i> Agregar
          </button>

        	 <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>          

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>

        <div class="card-body">
          Start creating your amazing application!
        </div>
        <!-- /.card-body -->
        
        <div>
          <script>
            var diaSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"); 
            var numSemana= new Array(5,6,0,1,2,3,4)
            
            // let today     = moment().format('YYYY-MM-DD');
            // let tomorrow  = moment().add(1,'days').format('YYYY-MM-DD');
            // let ayer1 = moment().add(-1, 'days').format('YYYY-MM-DD');     
            // let ayer2 = moment().subtract(1, 'days').format('YYYY-MM-DD');
            // let dia0 = moment('2021-10-17').format('d');
            // let dia1 = moment('2021-10-18').format('d');
            // let dia2 = moment('2021-10-19').format('d');
            // let dia3 = moment('2021-10-20').format('d');
            // let dia4 = moment('2021-10-21').format('d');
            // let dia5 = moment('2021-10-22').format('d');
            // let dia6 = moment('2021-10-23').format('d');

            // //let hoy = moment().format('DD');
            // document.write(today)
            // document.write('</br>');
            // document.write(tomorrow)
            // document.write('</br>');
            // document.write(ayer1) 
            // document.write('</br>');
            // document.write('You are '+moment('2016-08-10').fromNow()+ ' old');
            // document.write('</br>');
            // document.write('dia numero '+moment().format('d'));
            // document.write('</br>');
            // document.write(`dia domingo->${dia0} Lunes->${dia1} Martes->${dia2} Miercoles->${dia3} Jueves->${dia4} Viernes->${dia5} Sabado->${dia6} `);
            // let hoy=moment('2021-10-17').format('d');
            // document.write('</br>'+'numero de dia de hoy es: ',hoy);
            // document.write('</br>');
            // hoy=parseInt(hoy);
            // document.write('hoy es '+diaSemana[hoy]);
            // document.write('</br>');
            // document.write(diaSemana[0]);
            // document.write('</br>');
            // viernesanterior = moment().subtract(5, 'days').format('YYYY-MM-DD');
            // document.write(viernesanterior);
            // document.write('</br>');
            // hoy=moment('2021-10-17').format('d');
            // hoy=parseInt(hoy);
            // for (let index = 0; index<7; index++) {
            //   if(hoy==moment().format('d')){
            //     document.write('hoy es '+diaSemana[hoy]+' '+hoy);  
            //     document.write('</br>');
            //   }

            //   if(hoy===5){
            //     document.write('es v '+diaSemana[hoy]+' '+hoy);
            //     hoy++;
            //   }else if(hoy===6){
            //     document.write('dia s'+diaSemana[hoy]+' '+hoy);
            //   }else{
            //     document.write('dia +'+diaSemana[hoy]+' '+hoy);
            //     hoy++;
            //   }
            //   parseInt(hoy);
            //   document.write('</br>');
            // }

            document.write(numSemana);
            console.table(numSemana);
            document.write('</br>');
            document.write('hoy es ',moment().format('d'));
            document.write('</br>');

            fechavar='2021-10-14';
            hoy=moment(fechavar).format('d');
            hoy=parseInt(hoy);
            document.write('dia de la semana ',hoy+' '+ diaSemana[hoy]);
            document.write('</br>');
            let extrae=numSemana.indexOf(hoy);;
            extrae=parseInt(extrae);
            document.write(extrae);
            document.write('</br>');
            //sviernesanterior = moment().subtract(extrae, 'days').format('YYYY-MM-DD');
            viernesanterior = moment(fechavar).subtract(extrae, 'days').format('YYYY-MM-DD');
            document.write(viernesanterior);


 </script>
        </div>
        
        <div id="liveclock"></div>
        <div class="card-footer">
          Footer
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  