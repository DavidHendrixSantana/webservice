<?php

use src\DB\Exception\SQLException;
use src\Model\Model;
// require 'guzzle/vendor/autoload.php';
require './vendor/autoload.php';
use GuzzleHttp\Client;

/**
 * @author Arturo Villalpando
 */
class JobModel extends Model
{
    /**
     * @return array
     */
    public function getProveedores()
    {
        $sql = "SELECT id, proveedor, url_webservice, url_trabajo, solicitud FROM trabajos_proveedores WHERE activo = 1;";
        try {
            $query = $this->db->prepare($sql);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception  $e) {
            throw new SQLException($e);
        }
    }

    /**
     * @return array
     */
    public function updateJobsAPE($proveedor_id, $url_trabajo, $solicitudes = array())
    {
        $ids = array();

        $sql = "INSERT INTO trabajos_solicitudes
                (solicitud_id, proveedor_id, cno_id, prf_codigo_sofia, nombre, descripcion, empresa, 
                salario_mensual, jornada, meses_exp, horario, es_teletrabajo, uri, pais, departamento, municipio)
                VALUES
                (:solicitud_id, :proveedor_id, :cno_id, :prf_codigo_sofia, :nombre, :descripcion, :empresa,
                :salario_mensual, :jornada, :meses_exp, :horario, :es_teletrabajo, :uri, :pais,
                :departamento, :municipio)
                ON DUPLICATE KEY UPDATE
                proveedor_id = :proveedor_id, cno_id=:cno_id, prf_codigo_sofia=:prf_codigo_sofia, nombre=:nombre,
                descripcion=:descripcion, empresa=:empresa, salario_mensual=:salario_mensual, jornada=:jornada,
                meses_exp=:meses_exp, horario=:horario, es_teletrabajo=:es_teletrabajo, uri=:uri,
                pais=:pais, departamento=:departamento, municipio=:municipio, activo=1";
        foreach ($solicitudes as $key => $value) {
            $solicitud_id = (int) $value['SOLICITUD_ID'];
            $prf_codigo_sofia = substr($value['CNO_ID'], 0, 4);
            $es_teletrabajo = (strtolower($value['DEM_ES_TELETRABAJO']) == "no") ? 0 : 1;
            $uri = $url_trabajo . $solicitud_id;
            try {
                $query = $this->db->prepare($sql);
                $query->bindValue('solicitud_id', $solicitud_id, PDO::PARAM_INT);
                $query->bindValue('proveedor_id', $proveedor_id, PDO::PARAM_STR);
                $query->bindValue('cno_id', $value['CNO_ID'], PDO::PARAM_INT);
                $query->bindValue('prf_codigo_sofia', $prf_codigo_sofia, PDO::PARAM_INT);
                $query->bindValue('nombre', $value['NOMBRE_CARGO'], PDO::PARAM_STR);
                $query->bindValue('descripcion', $value['DESCRIPCIÓN_CNO'], PDO::PARAM_STR);
                $query->bindValue('empresa', $value['NOMBRE_EMPRESA'], PDO::PARAM_STR);
                $query->bindValue('salario_mensual', $value['SALARIO_MENSUAL'], PDO::PARAM_STR);
                $query->bindValue('jornada', $value['JORNADA_TRABAJO'], PDO::PARAM_STR);
                $query->bindValue('meses_exp', $value['MESES_EXP'], PDO::PARAM_STR);
                $query->bindValue('horario', $value['HORARIO'], PDO::PARAM_STR);
                $query->bindValue('es_teletrabajo', $es_teletrabajo, PDO::PARAM_BOOL);
                $query->bindValue('uri', $uri, PDO::PARAM_STR);
                $query->bindValue('pais', $value['PAIS'], PDO::PARAM_STR);
                $query->bindValue('departamento', $value['DEPARTAMENTO'], PDO::PARAM_STR);
                $query->bindValue('municipio', $value['MUNICIPIO'], PDO::PARAM_STR);
                $query->execute();
            } catch (Exception  $e) {
                echo($e);
                throw new SQLException($e);
            }
            $ids[] = $solicitud_id;
        }

        return $ids;
    }

    /**
     * @return array
     */
    public function updateJobsEmpleosGobMX($proveedor_id, $url_trabajo, $solicitudes = array())
    {
        $ids = array();

        $sql = "INSERT INTO trabajos_solicitudes
                (solicitud_id, proveedor_id, cno_id, nombre, descripcion, empresa, salario_mensual,
                meses_exp, uri, pais, departamento, municipio)
                VALUES
                (:solicitud_id, :proveedor_id, :cno_id, :nombre, :descripcion, :empresa,
                :salario_mensual, :meses_exp, :uri, :pais, :departamento, :municipio)
                ON DUPLICATE KEY UPDATE
                proveedor_id = :proveedor_id, cno_id=:cno_id, nombre=:nombre, descripcion=:descripcion,
                empresa=:empresa, salario_mensual=:salario_mensual, meses_exp=:meses_exp, uri=:uri,
                pais=:pais, departamento=:departamento, municipio=:municipio, activo=1";
        foreach ($solicitudes['content'] as $key => $value) {
            $solicitud_id = (int) $value['id'];
            // Necesitamos insertar el cargo para tener un catalogo, estos se insertan en la tabla trabajos indices
            $cno_id = $this->getCNO($proveedor_id, $value["tituloOferta"]);
            $uri = $url_trabajo . $solicitud_id;
            try {
                $query = $this->db->prepare($sql);
                $query->bindValue('solicitud_id', $solicitud_id, PDO::PARAM_INT);
                $query->bindValue('proveedor_id', $proveedor_id, PDO::PARAM_STR);
                $query->bindValue('cno_id', $cno_id, PDO::PARAM_INT);
                $query->bindValue('nombre', ($value['tituloOferta']), PDO::PARAM_STR);
                $query->bindValue('descripcion', ($value['descripcion']), PDO::PARAM_STR);
                $query->bindValue('empresa', ($value['nombreEmpresa']), PDO::PARAM_STR);
                $query->bindValue('salario_mensual', $value['salarioOfrecido'], PDO::PARAM_STR);
                $query->bindValue('meses_exp', $value['aniosExperiencia'], PDO::PARAM_STR);
                $query->bindValue('uri', $uri, PDO::PARAM_STR);
                $query->bindValue('pais', "Mexico", PDO::PARAM_STR);
                $query->bindValue('departamento', ($value['entidad']), PDO::PARAM_STR);
                $query->bindValue('municipio', ($value['municipio']), PDO::PARAM_STR);
                $query->execute();
            } catch (Exception  $e) {
                throw new SQLException($e);
                echo($e);
            }
            $ids[] = $solicitud_id;
        }

        return $ids;
    }

    public function updateJobsSPE($proveedor_id, $url_trabajo, $solicitudes)
    {
        $ids = array();
        
        $sql = "INSERT INTO trabajos_solicitudes
                (solicitud_id, proveedor_id, cno_id, prf_codigo_sofia, nombre, descripcion, empresa, 
                salario_mensual, jornada, meses_exp, horario, es_teletrabajo, uri, pais, departamento, municipio)
                VALUES
                (:solicitud_id, :proveedor_id, :cno_id, :prf_codigo_sofia, :nombre, :descripcion, :empresa,
                :salario_mensual, :jornada, :meses_exp, :horario, :es_teletrabajo, :uri, :pais,
                :departamento, :municipio)
                ON DUPLICATE KEY UPDATE
                proveedor_id = :proveedor_id, cno_id=:cno_id, prf_codigo_sofia=:prf_codigo_sofia, nombre=:nombre,
                descripcion=:descripcion, empresa=:empresa, salario_mensual=:salario_mensual, jornada=:jornada,
                meses_exp=:meses_exp, horario=:horario, es_teletrabajo=:es_teletrabajo, uri=:uri,
                pais=:pais, departamento=:departamento, municipio=:municipio, activo=1";
        
        $xml_snippet = simplexml_load_string( $solicitudes );
        $json_convert = json_encode( $xml_snippet );
        $json = json_decode( $json_convert );

    
        foreach ($json as $row){
            
            $data = json_decode($row, true);
            
            foreach($data as $solicitud){
                
                $solicitud_id = str_replace("-","",$solicitud['CodigoVacante']);
                $prf_codigo_sofia = substr($solicitud['CodigoVacante'], 0, 4);
                $es_teletrabajo = 0;
                $uri = $url_trabajo . $solicitud_id;
                try {
                    $query = $this->db->prepare($sql);
                    $query->bindValue('solicitud_id', $solicitud_id, PDO::PARAM_INT);
                    $query->bindValue('proveedor_id', $proveedor_id, PDO::PARAM_STR);
                    $query->bindValue('cno_id', $solicitud['CodigoVacante'], PDO::PARAM_INT);
                    $query->bindValue('prf_codigo_sofia', $prf_codigo_sofia, PDO::PARAM_INT);
                    $query->bindValue('nombre', ($solicitud['Cargo']), PDO::PARAM_STR);
                    $query->bindValue('descripcion', ($solicitud['VacTexto']), PDO::PARAM_STR);
                    $query->bindValue('empresa', ($solicitud['NombreSede']), PDO::PARAM_STR);
                    $query->bindValue('salario_mensual', $solicitud['VacRandoSalarial'], PDO::PARAM_STR);
                    $query->bindValue('jornada', $solicitud['VacJornada'], PDO::PARAM_STR);
                    $query->bindValue('meses_exp', "", PDO::PARAM_STR);
                    $query->bindValue('horario', "", PDO::PARAM_STR);
                    $query->bindValue('es_teletrabajo', 0, PDO::PARAM_BOOL);
                    $query->bindValue('uri', "", PDO::PARAM_STR);
                    $query->bindValue('pais', 'Colombia', PDO::PARAM_STR);
                    $query->bindValue('departamento', ($solicitud['VacDepartamento']), PDO::PARAM_STR);
                    $query->bindValue('municipio', ($solicitud['VacCiudad']), PDO::PARAM_STR);
                    $query->execute();
                } catch (Exception  $e) {
                    throw new SQLException($e);
                }
                $ids[] = $solicitud_id;
                 
            }
        }

        return $ids;
        
    }

    private function getCNO($proveedor_id, $nombre)
    {
        $sql = "SELECT cno_id FROM trabajos_indices WHERE proveedor_id = :proveedor_id AND lower(nombre) = :nombre";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue('proveedor_id', $proveedor_id, PDO::PARAM_INT);
            $query->bindValue('nombre', trim(strtolower($nombre)), PDO::PARAM_STR);
            $query->execute();

            $result = $query->fetch(PDO::FETCH_ASSOC);
            if($result) {
                $cno_id = $result['cno_id'];
            } else {
                $sql = "SELECT MAX(cno_id) AS cno_id FROM trabajos_indices WHERE proveedor_id = :proveedor_id";
                $query = $this->db->prepare($sql);
                $query->bindValue('proveedor_id', $proveedor_id, PDO::PARAM_INT);
                $query->execute();

                $result = $query->fetch(PDO::FETCH_ASSOC);
                if($result) {
                    $cno_id = $result['cno_id'] + 1;
                } else {
                    $cno_id = 1;
                }
                $this->createCNO($proveedor_id, $cno_id, $nombre);
            }
            return $cno_id;
        } catch (Exception  $e) {
            throw new SQLException($e);
        }
    }

    private function createCNO($proveedor_id, $cno_id, $nombre) {
        $sql = "SELECT cno_id FROM trabajos_indices WHERE proveedor_id = :proveedor_id AND lower(nombre) = :nombre";
        try {
            // Insert new CNO_ID
            $sql = "INSERT INTO trabajos_indices (proveedor_id, cno_id, nombre) VALUES (:proveedor_id, :cno_id, :nombre)";
            $query = $this->db->prepare($sql);
            $query->bindValue('proveedor_id', $proveedor_id, PDO::PARAM_INT);
            $query->bindValue('cno_id', $cno_id, PDO::PARAM_INT);
            $query->bindValue('nombre', trim($nombre), PDO::PARAM_STR);
            $query->execute();
        } catch (Exception  $e) {
            throw new SQLException($e);
        }
    }

    /**
     * @return boolean
     */
    public function deactiveJobs($ids = array())
    {

        $ids = implode(",", $ids);
        $sql = "UPDATE trabajos_solicitudes SET activo = 0 WHERE activo = 1 AND solicitud_id NOT IN ($ids)";
        try {
            $query = $this->db->prepare($sql);
            $query->execute();

            return $query->fetch();
        } catch (Exception  $e) {
            
            echo "Error de SQL: " . $e->getMessage();
        }
    }

    public function startProcessingJobs(){

        try {
            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => 'https://career-latam-pruebas.territorium.com/',
            ]);
            $res = $client->request('GET', 'proccessJobs', ['verify' => false]);
            $arr_res= json_decode($res->getBody());
            echo $arr_res;
        } catch (\Throwable $th) {
            echo $th;
        }
        return true;
       
    }

    public function updateJobsEmpleoCom($proveedor_id, $url_trabajo, $solicitudes)
    {

        $ids = array();
        
        $sql = "INSERT INTO trabajos_solicitudes
                (solicitud_id, proveedor_id, cno_id, prf_codigo_sofia, nombre, descripcion, empresa, 
                salario_mensual, jornada, meses_exp, horario, es_teletrabajo, uri, pais, departamento, municipio)
                VALUES
                (:solicitud_id, :proveedor_id, :cno_id, :prf_codigo_sofia, :nombre, :descripcion, :empresa,
                :salario_mensual, :jornada, :meses_exp, :horario, :es_teletrabajo, :uri, :pais,
                :departamento, :municipio)
                ON DUPLICATE KEY UPDATE
                proveedor_id = :proveedor_id, cno_id=:cno_id, prf_codigo_sofia=:prf_codigo_sofia, nombre=:nombre,
                descripcion=:descripcion, empresa=:empresa, salario_mensual=:salario_mensual, jornada=:jornada,
                meses_exp=:meses_exp, horario=:horario, es_teletrabajo=:es_teletrabajo, uri=:uri,
                pais=:pais, departamento=:departamento, municipio=:municipio, activo=1";
        

    
        foreach ($solicitudes as $solicitud){
            
                
                $solicitud_id = str_replace("-","",$solicitud['id']);
                $solicitud_name = str_replace(" ","-",strtolower($solicitud['title']));
                //$prf_codigo_sofia = substr($solicitud['CodigoVacante'], 0, 4);
                //$es_teletrabajo = 0;
                $uri = $url_trabajo . $solicitud_name . '/'. $solicitud_id;
                try {
                    $query = $this->db->prepare($sql);
                    $query->bindValue('solicitud_id', $solicitud_id, PDO::PARAM_INT);
                    $query->bindValue('proveedor_id', $proveedor_id, PDO::PARAM_STR);
                    $query->bindValue('cno_id', $solicitud['id'], PDO::PARAM_INT);
                    $query->bindValue('prf_codigo_sofia', "", PDO::PARAM_INT);
                    $query->bindValue('nombre', $solicitud['title'], PDO::PARAM_STR);
                    $query->bindValue('descripcion', $solicitud['description'], PDO::PARAM_STR);
                    $query->bindValue('empresa', $solicitud['companyName'], PDO::PARAM_STR);
                    $query->bindValue('salario_mensual', $solicitud['salaryInfo'], PDO::PARAM_STR);
                    $query->bindValue('jornada', "", PDO::PARAM_STR);
                    $query->bindValue('meses_exp', "", PDO::PARAM_STR);
                    $query->bindValue('horario', "", PDO::PARAM_STR);
                    $query->bindValue('es_teletrabajo',"", PDO::PARAM_BOOL);
                    $query->bindValue('uri', $uri, PDO::PARAM_STR);
                    $query->bindValue('pais', 'Colombia', PDO::PARAM_STR);
                    $query->bindValue('departamento', "", PDO::PARAM_STR);
                    $query->bindValue('municipio', $solicitud['city'], PDO::PARAM_STR);
                    $query->execute();
                } catch (PDOException $e) {
                    // Manejo de la excepción de SQL
                    echo "Error de SQL: " . $e->getMessage();
                }
                $ids[] = $solicitud_id;
                 
        }

        return $ids;
    }
}
