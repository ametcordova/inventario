document.addEventListener("DOMContentLoaded", function() {
    if (!annyang) {
        return alert("Lo siento, tu navegador no soporta el reconocimiento de voz :(");
    }else{
        console.log("Activado")
    }
    /*
    const $comandosReconocidos = document.querySelector("#comandosReconocidos"),
        $vozDetectada = document.querySelector("#vozDetectada");

    const loguearComandoReconocido = contenido => {
        $comandosReconocidos.innerHTML += contenido + "<br>";
    };
*/
    //const loguearVozDetectada = contenido => {
    //    $vozDetectada.innerHTML += contenido + "<br>";
    //};

    annyang.setLanguage("es-MX");

    let comandos = {
        "hola": () => {
            loguearComandoReconocido(`Hola mundo!`);
            console.log("hola")

        },
        
        "mexpei": () => {
            window.open('https://www.mexpei.com/wp/');
        },
        "reporte de inventario": () => {
            location.href='https://localhost/cervecentro/reporteinventario';
        },
        "ventas": () => {
            location.href='https://localhost/cervecentro/salidas';
            //window.open('https://localhost/cervecentro/salidas');
        },
        "reporte de ventas": () => {
            location.href='https://localhost/cervecentro/reportedeventas';
        },
        "reporte de compras": () => {
            location.href='https://localhost/cervecentro/adminalmacenes';
        },
        "compras": () => {
            location.href='https://localhost/cervecentro/entradas';
        },

        "buscar": () => {
            $('#selecProductoSal').select2('open');
        },

        "agregar": () => {
            $('#agregarProductos').click();
        },

        "guardar venta": () => {    
            $('#btnGuardar').click();
        },

        "Grabar": () => {    
            $('.swal-button--confirm').click();
        },

        "imprimir ticket": () => {    
            $('#saveRegTick').click();
        },

        "salir del sistema": () => {
            //$('#outsystem').trigger("click");
            location.href='https://localhost/cervecentro/salir';
        },

        "cantidad": () => {
            $('#cantSalida').focus();
        },
/*
        "dos": () => {
            $('#cantSalida').val(2);
        },

*/
        "cantidad *cant": cant => {
            //loguearComandoReconocido(`Ok te muestro el reporte de ventas de ${mes}`);
            $('#cantSalida').val(cant);
        }

    };

    annyang.addCommands(comandos);

    //annyang.addCallback("result", frases => {
    //    loguearVozDetectada(`<strong>Probablemente has dicho: </strong> <br> ${frases.join("<br>")}`);
    //});

    annyang.start();
});