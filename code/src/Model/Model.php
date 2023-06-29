<?php

/**
 * @author Arturo Villalpando Sanchez <arturo.villalpando.s@gmail.com>
 */

namespace src\Model;

use src\DB\DBConnect as connect;

/**
 * @class Model
 */
class Model
{
    /**
     * @var PDO
     */
    protected $db;

    /**
     * Initialization of objects
     */
    public function __construct()
    {
        $this->db = connect::getInstance();
    }
}