<?php
// Mostramos errores -> Pruebas
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
// Cargmos los autoloader para las clases

require 'guzzle/vendor/autoload.php';
use GuzzleHttp\Client;
try {
    $client = new Client;
    $client->setDefaultOption('verify', false);
    $response = $client->get('https://career-latam-pruebas.territorium.com/proccessJobs');
    echo $response;
    return $response;
} catch (\Throwable $th) {
    echo $th;
}
return true;


define('BASE_PATH', realpath(dirname(__FILE__)));
function my_autoloader($class) {
    $filename = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    include($filename);
}
spl_autoload_register('my_autoloader');
// Archivos requeridos
require_once "app/models/ReadWebService.php";
require_once "app/models/JobModel.php";
// Inicializamos objetos
$read = new ReadWebService();
$jobs = new JobModel();
// Obtenemos los proveedores
echo "Iniciando";

$proveedores = $jobs->getProveedores();
// Actualizamos la informacion
foreach($proveedores AS $proveedor => $key) {
    // Leemos el webservice y Actualizamos los datos
    // if($key['proveedor'] == "empleo.gob.mx") {
    //     $solicitudes = $read->read($key["url_webservice"], $key["solicitud"], "post");
    //     $ids = $jobs->updateJobsEmpleosGobMX($key["id"], $key["url_trabajo"], $solicitudes);
    //  } 
    if($key['proveedor'] == 'APE') {
        $solicitudes = $read->read($key["url_webservice"], $key["solicitud"]);
        $ids = $jobs->updateJobsAPE($key["id"], $key["url_trabajo"], $solicitudes);
    } 
    else if($key['proveedor'] == 'SPE'){
        $solicitudes = $read->readSPE($key["url_webservice"]);
        $ids = $jobs->updateJobsSPE($key["id"], $key["url_trabajo"], $solicitudes);
    }
        
    
    // Desactivamos los trabajos que ya no existen
    // $jobs->deactiveJobs($ids);
    // $jobs->startProcessingJobs();
}


// Lanzamos mensaje de finalizacion y salimos del Script
echo "Finalizado";
exit;