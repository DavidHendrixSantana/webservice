<?php

/**
 * @author Arturo Villalpando Sanchez <arturo.villalpando.s@gmail.com>
 */

namespace src\Files;

use src\Files\Exception\FileNotFoundException;
use src\Files\Exception\AccessDeniedException;
use src\Files\File;

/**
 * @class FileReader
 */
class FileReader extends File
{
    /**
     * Initialize
     *
     * @param String $file
     * @param char $mode
     */
    public function __construct($file = null, $mode = 'r')
    {
        parent::__construct($file, $mode);
    }

    /**
     * Read a whole file
     *
     * @return String $content
     * @throws AccessDeniedException
     */
    public function read()
    {
        if( !($read = fread($this->fp, filesize($this->file))) )
        {
            throw new AccessDeniedException("You have no rights or an error occurred read: " . $this->file);
        }
        else
        {
            $content = explode("\n", $read);

            return $content;
        }
    }

    /**
     * Read a line of a file
     *
     * @return String $line
     * @throws AccessDeniedException
     */
    public function readLine()
    {
        if( !($read = fread($this->fp, filesize($this->file))) )
        {
            throw new AccessDeniedException("You have no rights or an error occurred read: " . $this->file);
        }
        else
        {
            return $read;
        }
    }

    /**
     * Return a XML object
     *
     * @return SimpleXMLElement
     * @throws FileNotFoundException
     */
    public function readXML()
    {
        if( !($file = simplexml_load_file($this->file)) )
        {
            throw new FileNotFoundException("File not found:  " . $this->file);
        }
        else
        {
            return $file;
        }
    }

    /**
     * Read a JSON file
     *
     * @return JSONArray
     * @throws FileNotFoundException
     */
    public function readJSON()
    {
        if( !($read = file_get_contents($this->file)) )
        {
            throw new FileNotFoundException("File not found: " . $this->file);
        }
        else
        {
            $read = utf8_encode($read);
            $read = json_decode($read, true);

            return $read;
        }
    }

    /**
     * Read an INI file
     *
     * @param Boolean $mode
     * @return Array
     * @throws FileNotFoundException
     */
    public function readINI($mode)
    {
        if( !($read = parse_ini_file($this->file, $mode)) )
        {
            throw new FileNotFoundException("File not found: " . $this->file);
        }
        else
        {
            return $read;
        }
    }
}