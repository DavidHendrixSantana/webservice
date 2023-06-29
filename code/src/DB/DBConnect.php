<?php

/**
 * @author Arturo Villalpando Sanchez <arturo.villalpando.s@gmail.com>
 */

namespace src\DB;

use src\DB\Exception\ConfigException;
use src\Files\FileReader;

// PDO
use \PDO;
use \PDOException;

/**
 * @class DBConnect
 */
class DBConnect
{
    /**
     * Instance static instance DBConnect
     *
     * @var DBConnect
     */
    private static $instance;

    /**
     * Read db configuration
     *
     * @var FileReader
     */
    private static $file;

    /**
     * Content of the file
     *
     * @var Array
     */
    private static $data;

    /**
     * Initialize
     */
    private function __construct() {}

    /**
     * Singleton
     * @return DBConnect instance
     */
    public static function getInstance()
    {
        if ( !isset(self::$instance) ) {
            //self::$instance = new self();
            // Read the file
            self::$file = new FileReader($_SERVER['DOCUMENT_ROOT'] . "\\clr-solicitudes\\app\\config\\Database.ini");
            // self::$file = new FileReader(".\code\config\Database.ini");
            self::$data = self::$file->readINI(False);
            /**
             * PDO Configuration
             */
            try
            {
                $dsn = self::$data['driver'] .
                ':host=' . self::$data['host'] .
                ';dbname=' . self::$data['dbname'];

                self::$instance = new PDO($dsn, self::$data['user'], self::$data['password'],
                    array( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)
                );
                self::$instance->setAttribute(PDO::ATTR_PERSISTENT, $dsn);
            }
            catch (PDOException $e)
            {
                die("PDO CONNECTION ERROR: " . $e->getMessage() . "<br/>");
            }
        }
        return self::$instance;
    }

    public function getMyDB()
    {
        if (self::$instance instanceof PDO) {
            return self::$instance;
        }
    }

    /**
     * @throws ConfigException
     */
    public function __clone()
    {
        throw new ConfigException("Clones not allowed", E_USER_ERROR);
    }

    /**
     * @throws ConfigException
     */
    public function __sleep()
    {
        throw new ConfigException("Sleep not allowed", E_USER_ERROR);
    }

    /**
     * @throws ConfigException
     */
    public function __wakeup()
    {
        throw new ConfigException("Wakup not allowed", E_USER_ERROR);
    }

    /**
     * Destroy
     */
    public function __destruct()
    {
        // Close DB Connection
        self::$instance = null;
        // Destroy instance
        //$this->instance = null;
    }
}