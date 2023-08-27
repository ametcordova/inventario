<?php

$deslogin=new controladorUsuarios();
$respuesta=$deslogin::ctrDesloguearse();
if ($respuesta) {
	session_destroy();
	echo '<script>
   
		window.location = "ingreso";
   
	</script>';
	
} else {
    // La desconección no fue exitosa, maneja el error de acuerdo a tus necesidades
    echo "Error al intentar desloguear al usuario.";
}

