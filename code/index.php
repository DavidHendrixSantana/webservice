<?php
// Mostramos errores -> Pruebas
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
// Cargmos los autoloader para las clases




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
$ids = [];
// Obtenemos los proveedores

$proveedores = $jobs->getProveedores();
// Actualizamos la informacion
foreach($proveedores AS $proveedor => $key) {
        // Leemos el webservice y Actualizamos los datos

        if($key['proveedor'] == 'APE') {
            echo "\nObteniendo vacantes APE";
            $solicitudes = $read->read($key["url_webservice"], $key["solicitud"]);
            $id_ape = $jobs->updateJobsAPE($key["id"], $key["url_trabajo"], $solicitudes);
            $ids= array_merge($ids,$id_ape);
         

            
        }
        if($key['proveedor'] == "empleo.com/co"){
            echo "\nObteniendo vacantes elempleo";

            //$cookie = 'cert_Origin=directo; cert_Origin=directo; visid_incap_643718=52m69tFYTKevPRtwKTV1JkF7g2QAAAAAQUIPAAAAAAAwpy0zMIirDnYQUb2EqkYk; s_fid=1521BEB84BA4683D-303DB479DEA73659; _rtbhouse_source_=direct; hubspotutk=c705b5cec784128bc0bead6d342623ed; __RequestVerificationToken_L2Nv0=to4eS5VeBzEWAwwfWSXbOFjJ782FolRHlX8orCUEuiTq-0Xlcl9xEVtde4QRb6Bau3ElHaQ3W_SFGiyz_ufHF8qHJys1; _gcl_au=1.1.1626431012.1688660782; s_cc=true; s_cm=Other%20Natural%20Referrersundefinedstatics.teams.cdn.office.net; s_v8=%5B%5B%27n%2Fa%27%2C%271686760657070%27%5D%2C%5B%27other%2520natural%2520referrers%253A%2520statics.teams.cdn.office.net%27%2C%271688660782609%27%5D%5D; s_sq=%5B%5BB%5D%5D; _gid=GA1.2.385727366.1688660783; cebs=1; _ce.clock_event=1; __hssrc=1; /co/offers_home_not_logged=/wEyphUAAQAAAP////8BAAAAAAAAAAQBAAAAqAJTeXN0ZW0uQ29sbGVjdGlvbnMuR2VuZXJpYy5MaXN0YDFbW1N5c3RlbS5Db2xsZWN0aW9ucy5HZW5lcmljLkxpc3RgMVtbTGVhZGVyU2VhcmNoLkVsRW1wbGVvLk1WQy5Nb2RlbHMuSm9iT2ZmZXJzTGFzdFNlYXJjaERldGFpbE1vZGVsLCBMZWFkZXJTZWFyY2guRWxFbXBsZW8uTVZDLCBWZXJzaW9uPTEuMC4wLjAsIEN1bHR1cmU9bmV1dHJhbCwgUHVibGljS2V5VG9rZW49bnVsbF1dLCBtc2NvcmxpYiwgVmVyc2lvbj00LjAuMC4wLCBDdWx0dXJlPW5ldXRyYWwsIFB1YmxpY0tleVRva2VuPWI3N2E1YzU2MTkzNGUwODldXQMAAAAGX2l0ZW1zBV9zaXplCF92ZXJzaW9uAwAAuAFTeXN0ZW0uQ29sbGVjdGlvbnMuR2VuZXJpYy5MaXN0YDFbW0xlYWRlclNlYXJjaC5FbEVtcGxlby5NVkMuTW9kZWxzLkpvYk9mZmVyc0xhc3RTZWFyY2hEZXRhaWxNb2RlbCwgTGVhZGVyU2VhcmNoLkVsRW1wbGVvLk1WQywgVmVyc2lvbj0xLjAuMC4wLCBDdWx0dXJlPW5ldXRyYWwsIFB1YmxpY0tleVRva2VuPW51bGxdXVtdCAgJAgAAAAEAAAABAAAABwIAAAAAAQAAAAQAAAADtgFTeXN0ZW0uQ29sbGVjdGlvbnMuR2VuZXJpYy5MaXN0YDFbW0xlYWRlclNlYXJjaC5FbEVtcGxlby5NVkMuTW9kZWxzLkpvYk9mZmVyc0xhc3RTZWFyY2hEZXRhaWxNb2RlbCwgTGVhZGVyU2VhcmNoLkVsRW1wbGVvLk1WQywgVmVyc2lvbj0xLjAuMC4wLCBDdWx0dXJlPW5ldXRyYWwsIFB1YmxpY0tleVRva2VuPW51bGxdXQkDAAAADQMMBAAAAFBMZWFkZXJTZWFyY2guRWxFbXBsZW8uTVZDLCBWZXJzaW9uPTEuMC4wLjAsIEN1bHR1cmU9bmV1dHJhbCwgUHVibGljS2V5VG9rZW49bnVsbAQDAAAAtgFTeXN0ZW0uQ29sbGVjdGlvbnMuR2VuZXJpYy5MaXN0YDFbW0xlYWRlclNlYXJjaC5FbEVtcGxlby5NVkMuTW9kZWxzLkpvYk9mZmVyc0xhc3RTZWFyY2hEZXRhaWxNb2RlbCwgTGVhZGVyU2VhcmNoLkVsRW1wbGVvLk1WQywgVmVyc2lvbj0xLjAuMC4wLCBDdWx0dXJlPW5ldXRyYWwsIFB1YmxpY0tleVRva2VuPW51bGxdXQMAAAAGX2l0ZW1zBV9zaXplCF92ZXJzaW9uBAAAQUxlYWRlclNlYXJjaC5FbEVtcGxlby5NVkMuTW9kZWxzLkpvYk9mZmVyc0xhc3RTZWFyY2hEZXRhaWxNb2RlbFtdBAAAAAgICQUAAAAEAAAABAAAAAcFAAAAAAEAAAAEAAAABD9MZWFkZXJTZWFyY2guRWxFbXBsZW8uTVZDLk1vZGVscy5Kb2JPZmZlcnNMYXN0U2VhcmNoRGV0YWlsTW9kZWwEAAAACQYAAAAJBwAAAAkIAAAACQkAAAAFBgAAAD9MZWFkZXJTZWFyY2guRWxFbXBsZW8uTVZDLk1vZGVscy5Kb2JPZmZlcnNMYXN0U2VhcmNoRGV0YWlsTW9kZWwGAAAAEzxJZD5rX19CYWNraW5nRmllbGQWPFRpdGxlPmtfX0JhY2tpbmdGaWVsZBw8UHVibGlzaERhdGU+a19fQmFja2luZ0ZpZWxkHDxEZXNjcmlwdGlvbj5rX19CYWNraW5nRmllbGQXPFNlb1VybD5rX19CYWNraW5nRmllbGQXPFNhbGFyeT5rX19CYWNraW5nRmllbGQAAQEBAQEIBAAAAI6xZnAGCgAAADNFc3R1ZGlhbnRlIGVuIGdlc3Rpw7NuIGJhbmNhcmlhIHkgZW50aWRhZGVzIGZpbmEuLi4GCwAAAAwwNy9KdWwuLzIwMjMGDAAAAHVTb21vcyB1bmEgZGUgbGFzIGVudGlkYWRlcyBsaWRlcmVzIGVuIE1pY3JvZmluYW56YXMgeSB0ZSBpbnZpdGFtb3MgYSBlc2NyaWJpciBqdW50b3MgaGlzdG9yaWFzIGRlIHByb2dyZXNvIHF1ZSB0cmEuLi4GDQAAAGZodHRwczovL3d3dy5lbGVtcGxlby5jb20vY28vb2ZlcnRhcy10cmFiYWpvL2VzdHVkaWFudGUtZW4tZ2VzdGlvbi1iYW5jYXJpYS15LWVudGlkYWRlcy1maW5hLzE4ODU3NzgzMTgGDgAAAAtNZW5vcyBkZSAkMQEHAAAABgAAAOQ2Z3AGDwAAABdBbmFsaXN0YSBkZSBpbm5vdmFjaW9uIAYQAAAADDA2L0p1bC4vMjAyMwYRAAAAe0VuIFByb3RlY2Npw7NuIHF1ZXJlbW9zIGNvbnRhciBjb24gdGFsZW50b3MgYXBhc2lvbmFkb3MgcG9ywqBsYSBpbm5vdmFjacOzbiB5IHRyYW5zZm9ybWFjacOzbiBkZSBwcm9jZXNvcy4KCsKgCgpNaXNpw7NuIC4uLgYSAAAATWh0dHBzOi8vd3d3LmVsZW1wbGVvLmNvbS9jby9vZmVydGFzLXRyYWJham8vYW5hbGlzdGEtZGUtaW5ub3ZhY2lvbi8xODg1ODEyNDUyBhMAAAAUU2FsYXJpbyBjb25maWRlbmNpYWwBCAAAAAYAAADONmdwBhQAAAAdVG9ybmVyby9hIENOQyAxNTk2NTg3NTU4LjQzMDEGFQAAAAwwNi9KdWwuLzIwMjMGFgAAAHVOL0EgDQogSW1wb3J0YW50ZSBlbXByZXNhIGFsaWFkYSB1YmljYWRhIGVuIExhIGVzdHJlbGxhICByZXF1aWVyZSBwYXJhIHN1IGVxdWlwbyBkZSB0cmFiYWpvICBUb3JuZXJvL2EgQ05DIGNvbiBleHAuLi4GFwAAAFNodHRwczovL3d3dy5lbGVtcGxlby5jb20vY28vb2ZlcnRhcy10cmFiYWpvL3Rvcm5lcm8tYS1jbmMtMTU5NjU4NzU1ODQzMDEvMTg4NTgxMjQzMAYYAAAACSQxLDUgYSAkMgEJAAAABgAAAMs2Z3AGGQAAADNMw61kZXIgc2VndXJpZGFkIHkgc2FsdWQgZW4gZWwgdHJhYmFqbyAxNjI2MTE4NjIuLi4GGgAAAAwwNi9KdWwuLzIwMjMGGwAAAHdFbXByZXNhIHViaWNhZGEgZW4gbGEgY2l1ZGFkIGRlIE1lZGVsbMOtbiByZXF1aWVyZSBMw61kZXIgc2VndXJpZGFkIHkgc2FsdWQgZW4gZWwgdHJhYmFqbywgY29uIGV4cGVyaWVuY2lhIGRlIGNpbmNvIC4uLgYcAAAAZmh0dHBzOi8vd3d3LmVsZW1wbGVvLmNvbS9jby9vZmVydGFzLXRyYWJham8vbGlkZXItc2VndXJpZGFkLXktc2FsdWQtZW4tZWwtdHJhYmFqby0xNjI2MTE4NjIvMTg4NTgxMjQyNwkTAAAACw==; _ce.clock_data=-17%2C187.190.133.9%2C1%2C5f0ff5d8799ed4c0ed355fa474a7bbc2; incap_ses_1363_643718=BBUMOdrjZ23PzDUC9lnqEuwUqGQAAAAA3jH794i+qeT/d72mdjyNjQ==; s_vnum=1688930371240%26vn%3D5; s_invisit=true; s_lv_s=Less%20than%201%20day; __RequestVerificationToken_Ls2Api=ReXK/sT+xOItz6iOd4G7a2V3rAonOY8TjyT7YLntgZXTQyrMDwA1GVzORfzbRF4eRdN55n3Y4fZY+v88OPNa9h2T09PUlMG7NhRP3jDXFv21Rihv808+ceb3reiHTRVc4XtL9ubUr+4CLI7QqBIoKnN/RZQyRXirmIv2WPM/YBU=; ee-co-mvc=ffffffffaf190e2d45525d5f4f58455e445a4a423660; __hstc=152428262.c705b5cec784128bc0bead6d342623ed.1686338371917.1688660783454.1688738893223.4; /co/resultjoboffers_search_history=/wEyvwYAAQAAAP////8BAAAAAAAAAAwCAAAAVkxlYWRlclNlYXJjaC5NVkMuQ29tbW9uLlN1cHBvcnQsIFZlcnNpb249MS4wLjAuMCwgQ3VsdHVyZT1uZXV0cmFsLCBQdWJsaWNLZXlUb2tlbj1udWxsBAEAAAC3AVN5c3RlbS5Db2xsZWN0aW9ucy5HZW5lcmljLkxpc3RgMVtbTGVhZGVyU2VhcmNoLk1WQy5Db21tb24uU3VwcG9ydC5Nb2RlbHMuSm9iT2ZmZXJDb29raWVNb2RlbCwgTGVhZGVyU2VhcmNoLk1WQy5Db21tb24uU3VwcG9ydCwgVmVyc2lvbj0xLjAuMC4wLCBDdWx0dXJlPW5ldXRyYWwsIFB1YmxpY0tleVRva2VuPW51bGxdXQMAAAAGX2l0ZW1zBV9zaXplCF92ZXJzaW9uBAAAPExlYWRlclNlYXJjaC5NVkMuQ29tbW9uLlN1cHBvcnQuTW9kZWxzLkpvYk9mZmVyQ29va2llTW9kZWxbXQIAAAAICAkDAAAAAQAAAA8AAAAHAwAAAAABAAAABAAAAAQ6TGVhZGVyU2VhcmNoLk1WQy5Db21tb24uU3VwcG9ydC5Nb2RlbHMuSm9iT2ZmZXJDb29raWVNb2RlbAIAAAAJBAAAAA0DBQQAAAA6TGVhZGVyU2VhcmNoLk1WQy5Db21tb24uU3VwcG9ydC5Nb2RlbHMuSm9iT2ZmZXJDb29raWVNb2RlbAcAAAAcPEZpbHRlclF1ZXJ5PmtfX0JhY2tpbmdGaWVsZCE8RmlsdGVyU2FsYXJ5SW5mbz5rX19CYWNraW5nRmllbGQbPEZpbHRlckNpdHk+a19fQmFja2luZ0ZpZWxkIjxGaWx0ZXJQdWJsaXNoRGF0ZT5rX19CYWNraW5nRmllbGQbPEZpbHRlckFyZWE+a19fQmFja2luZ0ZpZWxkIzxGaWx0ZXJXb3JrTW9kYWxpdHk+a19fQmFja2luZ0ZpZWxkGTxQYWdlU2l6ZT5rX19CYWNraW5nRmllbGQBBwAAAAAACAgICAgIAgAAAAoJBQAAAAAAAAAAAAAAAAAAAAAAAABkAAAADwUAAAAAAAAACAs=; gpv_pn=elempleo%3A%20personas%3A%20resultados%20de%20busqueda; s_nr=1688739351375-Repeat; s_lv=1688739351375; s_v10=%5B%5B%27Typed%2FBookmarked%27%2C%271688737326692%27%5D%2C%5B%27Typed%2FBookmarked%27%2C%271688738901506%27%5D%2C%5B%27Typed%2FBookmarked%27%2C%271688738930664%27%5D%2C%5B%27Typed%2FBookmarked%27%2C%271688739111246%27%5D%2C%5B%27Typed%2FBookmarked%27%2C%271688739351377%27%5D%5D; _ga_253X7LF7HE=GS1.1.1688737325.8.1.1688739352.58.0.0; cebsp_=10; _ce.s=v~76b5f71ea598efa584de155bb323aa2b512ae964~lcw~1688737327297~vpv~0~v11.rlc~1688739352279~lcw~1688739352280; __hssc=152428262.3.1688738893223; _ga_04GE8EQG8F=GS1.1.1688737325.8.1.1688739352.58.0.0; _ga=GA1.1.1362898665.1686338371';
            $token = '__requestverificationtoken: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjEiLCJuYW1lIjoiVmFsaWRhdGVBbnRpRm9yZ2VyeVRva2VuV2ViQXBpQXR0cmlidXRlIn0=.178aJgnJctRxIfCFJ3vGmKKcIzaT9PFQTN8sOaF+11cSLeoDGYHVcGG41TQBK1uKAAOkso+bxp/hjbXmxvFszD+D/KrhGKG2KjTwRyd6r3T1Eol9zMDXpj/ciPcB+x70Kyh4/gbCJZtMXn7fwVNeyHnjE9c+cAcRX4aO8f6kKa0=';
            
            $url = 'https://www.elempleo.com/co/ofertas-empleo/';

            // Incluir la biblioteca PHP Simple HTML DOM Parser
            require_once './lib/simplehtmldom_1_9_1/simple_html_dom.php';

            // Obtener el contenido de la página
            $html = file_get_contents($url);

            // Obtener las cookies de la respuesta HTTP
            $cookies = array();
            foreach ($http_response_header as $header) {
                if (strpos($header, 'Set-Cookie:') !== false) {
                    $cookie = str_replace('Set-Cookie: ', '', $header);
                    $cookies[] = $cookie;
                }
            }

            $my_cookie = '';

            // Mostrar las cookies obtenidas
            foreach ($cookies as $cookie) {
                $my_cookie = $my_cookie . $cookie . ';';
            }

            $solicitudes = $read->readEmpleoCom($key["url_webservice"], $my_cookie, $token);
            $ids_empleo = $jobs->updateJobsEmpleoCom($key["id"], $key["url_trabajo"], $solicitudes);
            $ids= array_merge($ids,$ids_empleo);


        }
    }
    echo("\n Total procesadas:  ");
    echo(count($ids));
    echo "\nActualizando estatus de vacantes";
    $jobs->deactiveJobs($ids);
echo "\nFinalizado";
exit;