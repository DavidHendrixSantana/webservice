<?php

/**
 * @author Arturo Villalpando Sanchez <arturo.villalpando.s@gmail.com>
 */

namespace src\Files\Exception;

/**
 * @class IOException
 */
class IOException extends \RuntimeException
{
    /**
     * Rewrite Method
     *
     * @return String
     */
    public function __toString()
    {
        return 'IOException : ' . __CLASS__ . ' ['.$this->code.'] : ' .
            $this->message;
    }
}