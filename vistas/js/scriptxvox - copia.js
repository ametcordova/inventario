document.addEventListener("DOMContentLoaded", function() {
    if (!annyang) {
        return alert("Lo siento, tu navegador no soporta el reconocimiento de voz :(");
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

    };

    annyang.addCommands(comandos);

    //annyang.addCallback("result", frases => {
    //    loguearVozDetectada(`<strong>Probablemente has dicho: </strong> <br> ${frases.join("<br>")}`);
    //});

    annyang.start();
});