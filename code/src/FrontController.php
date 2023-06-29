<?php

/**
 * @author Arturo Villalpando Sanchez <arturo.villalpando.s@gmail.com>
 * @date 05-08-2014
 * @version 0.1
 */

namespace src;

/**
 * @class FrontController
 * @description Clase encargada de controlar la aplicacion
 */
class FrontController
{
    /**
     * @var static FrontController $instance
     */
    private static $instance;

    /**
     * Contructor de la clase
     * @return void
     */
    private function __construct(){}

    /**
     * Funcion Singleton
     * @return Autoloader - Instancia de la clase
     */
    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            $object = __CLASS__;
            self::$instance = new $object;
        }

        return self::$instance;
    }

    /**
     * __clone
     * @throws Exception
     * @return void
     */
    public function __clone()
    {
        throw new \Exception("No se permiten clones", E_USER_ERROR);
    }

    /**
     * __sleep
     * @throws Exception
     * @return void
     */
    public function __sleep()
    {
        throw new \Exception("No se puede serealizar singleton", E_USER_ERROR);
    }

    /**
     * __wakeup
     * @throws Exception
     * @return void
     */
    public function __wakeup()
    {
        throw new \Exception("No se puede deserealizar singleton", E_USER_ERROR);
    }

    /**
     * Función qu inicializa la aplicación, creando la configuración y routeando
     *  la peticion del usuario
     */
    public function init()
    {
        // Configuration Files
        //$config = "app/config/Config.ini";

        // Create a configuration
        //new Config($config);
    }
}
