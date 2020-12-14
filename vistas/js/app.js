var appFamilias =  new Vue({
    el: '#app',
    data:{
      familias:[],
      name:'',
      familia:'',
      ultusuario:'',
      ultmodificacion:'',
      dataTable:null,
    },
  methods:{
      addFamilia(){
          this.dataTable.row.add([
            this.id,
            '<a href="#">'+this.name+'</a>',
            this.ultmodificacion,
            '<a href="#" class="btn btn-info" >editar</a></td> <a href="#" class="btn btn-danger" >Borrar</a></td>'
          ]).draw(false)
            //this.id='';
            this.name='';
      },

      inicializar(){
        //this.$nextTick(function() {    
            //(async () => {   
              $.ajax({
                "method": "GET",
                "url": 'ajax/family.ajax.php?op=listarFamilias',
                dataType: 'json'
              })
              .then((res) => {
                console.log(res)
                this.familias = res;
                this.initDtt();
              });
            //})();  //fin del async
          //})  
      },
          
      inicializar2(){
        this.initDtt();
      },

      showDrawUser(person) {
        console.log('showDrawUser', person);
        alert("entra")
      },

      initDtt() {
        $(document).ready(() => {
          if ( $.fn.dataTable.isDataTable( '#fam-table' ) ) {
            //$('#user-table').DataTable().ajax.reload(null, false);
            //tablefamilia.ajax.reload( null, false );
            console.log("entra aqui 2a vez")
          }else {
            console.log("entra aki 1a vez")
            $('#fam-table').dataTable( {
              "lengthMenu": [ [10, 25, 50,100, -1], [10, 25, 50, 100, "Todos"] ],
              "language": {
           "sProcessing":     "Procesando...",
               "sLengthMenu":     "Mostrar _MENU_ registros &nbsp",
               "sZeroRecords":    "No se encontraron resultados",
               "sEmptyTable":     "Ningún dato disponible en esta tabla",
               "sInfo":           "Mostrar registros del _START_ al _END_ de un total de _TOTAL_",
           "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
           "sInfoPostFix":    "",           
               "sSearch":         "Buscar:",
               "sInfoThousands":  ",",
               "sLoadingRecords": "Cargando...",
               "oPaginate": {
           "sFirst":    "Primero",
           "sLast":     "Último",
           "sNext":     "Siguiente",
           "sPrevious": "Anterior"}
               },
           "oAria": {
             "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
             "sSortDescending": ": Activar para ordenar la columna de manera descendente"
           },
               dom: '<clear>Bfrtip',
               buttons: [
                   'copyHtml5',
                   'excelHtml5',
                   'csvHtml5',
                   {
                       extend: 'pdfHtml5',
                       orientation: 'landscape',
                       title: "Reporte de Compras",
                       filename: 'reportecompras',
                       customize: function ( doc ) {
                           pdfMake.createPdf(doc).open();
                       },
                   },
                   
              {
                   extend: 'print',
                   text: 'Imprimir',
                   autoPrint: false            //TRUE para abrir la impresora
               }],     
           initComplete: function () {			//botones pequeños y color verde
                   var btns = $('.dt-button');
                   btns.removeClass('dt-button');
                   btns.addClass('btn btn-success btn-sm');
                 },         
           "ajax":
               {
                 url: 'ajax/family.ajax.php?op=listarFamilias',
                 type : "get",
                 dataType : "json",	
                 dataSrc: '',
                 error: function(e){
                   console.log(e.responseText);
                           }
                       },
           "bDestroy": true,
           "iDisplayLength": 10,//Paginación
             "order": [[ 1, "desc" ]]//Ordenar (columna,orden)
         }).DataTable();


          } //fin del if    //$.each(JSON.parse(data), function(key,value){  
        });
      },

      btnBorrar:function(id,descripcion){        
          Swal.fire({
            title: 'Esta seguro Eliminar item '+descripcion+'?',
            text: "Al eliminarlo no podras recuperarlo!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No, cancelar!',
            confirmButtonText: 'Si, Borrarlo!'
          }).then((result) => {
          if (result.value) {
              const datos = new FormData();
              datos.append('itemEliminar', id);
            (async () => {   
              await fetch('ajax/family.ajax.php?op=eliminarFamilia', {
                method: 'POST',
                body: datos
              })

                .then(function(response) {
                  console.log('response.ok =', response.ok);
                  console.log('response.status =', response.status);
                  console.log('response.statusText =', response.statusText);
                  return response.text();
                })

              .then((data)=>{
                  if(data=="success"){
                    this.inicializar();
                    swal.fire("Registro Eliminado")
                  }else{
                    swal.fire("Registro no se puedo eliminar"+data)
                  }
                })
                .catch(function(err) {
                    console.error(err);
                });
            })();  //fin del async
          }else{
            return false;
          }

        })
        
      }


},

  
  mounted(){
    console.log("entra a inicializar")
    this.inicializar2();
  },  

    created(){
      console.log("entra aqui VUE1")
    }

})  //fin de new

/*
              "ajax": {
                "url": "ajax/family.ajax.php?op=listarFamilias",
                "dataSrc": ''
              }
            });

*/