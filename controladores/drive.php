
// Cargar la biblioteca de cliente de Google
require_once 'Google/autoload.php';

// Autenticar al cliente
$client = new Google_Client();
$client->setClientId('TU_ID_DE_CLIENTE');
$client->setClientSecret('TU_SECRETO_DE_CLIENTE');
$client->setRedirectUri('TU_URL_DE_REDIRECCION');
$client->setScopes(array('https://www.googleapis.com/auth/drive'));
$authUrl = $client->createAuthUrl();

// Autenticar con el token de acceso
if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

// Configurar el servicio de Google Drive
$service = new Google_Service_Drive($client);

// Cargar el archivo en una variable
$file = new Google_Service_Drive_DriveFile();
$file->setName('Nombre del archivo');
$file->setDescription('Descripción del archivo');
$file->setMimeType('MIME del archivo');

$data = file_get_contents('RUTA_DEL_ARCHIVO');
$createdFile = $service->files->create($file, array(
    'data' => $data,
    'mimeType' => 'MIME del archivo',
    'uploadType' => 'multipart'
));

// Imprimir el ID del archivo subido
echo 'Archivo subido con éxito, ID del archivo: ' . $createdFile->getId();
