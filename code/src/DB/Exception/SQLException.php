<?php

/**
 * @author Arturo Villalpando Sanchez <arturo.villalpando.s@gmail.com>
 */

namespace src\DB\Exception;

/**
 * @class ConfigException
 */
class SQLException extends \Exception
{
    /**
     * Rewrite String method
     *
     * @return type
     */
    public function __toString()
    {
        return "SQL Exception ".$this->getCode().": An error occurred ";
    }
}