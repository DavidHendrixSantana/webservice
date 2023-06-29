<?php

/**
 * @author Arturo Villalpando Sanchez <arturo.villalpando.s@gmail.com>
 */

namespace src\Files\Exception;

/**
 * @class FileValidateException
 */
class FileValidationException extends \Exception
{
    /**
     * Rewrite Method
     *
     * @return String
     */
    public function __toString()
    {
        return 'File Validate Exception : ' . __CLASS__ . ' ['.$this->code.'] : ' .
            $this->message;
    }
}