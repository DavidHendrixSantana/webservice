<?php

/**
 * @author Arturo Villalpando Sanchez <arturo.villalpando.s@gmail.com>
 */

namespace src\DB\Exception;

/**
 * @class ConfigException
 */
class ConfigException extends \BadMethodCallException
{
    /**
     * Rewrite String method
     *
     * @return type
     */
    public function __toString()
    {
        return 'Configuration Exception : ' . __CLASS__ . ' ['.$this->code.'] : ' .
            $this->message;
    }
}