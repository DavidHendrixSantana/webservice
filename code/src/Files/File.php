<?php

/**
 * @author Arturo Villalpando Sanchez <arturo.villalpando.s@gmail.com>
 */

namespace src\Files;

use src\Files\Exception\FileNotFoundException;
use src\Files\Exception\AccessDeniedException;

/**
 * @class File
 */
class File
{
    /**
     * @var fp
     */
    protected $fp = "";

    /**
     * @var File String
     */
    protected $file = "";

    /**
     * Mode of read or write a file
     *
     * @var char
     */
    protected $mode = "";

    /**
     * Initialize
     *
     * @param String $file - Name of file
     * @param char $mode - Mode of read or write
     */
    protected function __construct($file = null, $mode = 'r')
    {
        $this->file = $file;
        $this->mode = $mode;
    }

    /**
     * Opened a file
     *
     * @throws FileNotFoundException
     */
    public function open()
    {
        if( !($this->fp = fopen($this->file, $this->mode)) )
        {
            throw new FileNotFoundException("File not found in: " . $this->file);
        }
    }

    /**
     * Exist a file
     *
     * @throws FileNotFoundException
     */
    public function exists()
    {
        if( !(file_exists($this->file)) )
        {
            throw new FileNotFoundException("File not found in: " . $this->file);
        }
    }

    /**
     * Create a file with data, data can be empty
     *
     * @param String $data - Content of the file
     * @throws AccessDeniedException
     */
    protected function create($data = "")
    {
        if( !(fwrite($this->fp, $data)) )
        {
            throw new AccessDeniedException("Access denied for create file in: " . $this->file);
        }
    }

    /**
     * Close a file
     *
     * @throws FileNotFoundException
     */
    protected function close()
    {
        if( !(fclose($this->fp)) )
        {
            throw new FileNotFoundException("The file is not already open or is still close in: " . $this->file);
        }
    }

    /**
     * Delete a file
     *
     * @throws FileNotFoundException
     */
    public function delete()
    {
        if( !(unlink($this->file)) )
        {
            throw new AccessDeniedException("Access denied for delete file in: " . $this->file);
        }
    }

    /**
     * Rewrite a file mode
     *
     * @param char $mode
     */
    public function setMode($mode = 'r')
    {
        $this->mode = $mode;
    }

    /**
     * Get the file mode
     *
     * @return char
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Generates an aleatorio name of a file
     *
     * @return String
     */
    protected function generateName()
    {
        // Created a name of 11 aleatorios digits.
        $filename = substr(md5(uniqid(rand())), 0, 12);

        // Return the filename
        return $filename;
    }
}