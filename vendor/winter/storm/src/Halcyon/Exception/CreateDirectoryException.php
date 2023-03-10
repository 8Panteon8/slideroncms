<?php namespace Winter\Storm\Halcyon\Exception;

use RuntimeException;

class CreateDirectoryException extends RuntimeException
{
    /**
     * Name of the affected directory path.
     *
     * @var string
     */
    protected $invalidPath;

    /**
     * Set the affected directory path.
     *
     * @param  string   $path
     * @return $this
     */
    public function setInvalidPath($path)
    {
        $this->invalidPath = $path;

        $this->message = "Error creating directory [{$path}]. Please check write permissions.";

        return $this;
    }

    /**
     * Get the affected directory path.
     *
     * @return string
     */
    public function getInvalidPath()
    {
        return $this->invalidPath;
    }
}
