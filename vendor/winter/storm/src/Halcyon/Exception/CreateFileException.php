<?php namespace Winter\Storm\Halcyon\Exception;

use RuntimeException;

class CreateFileException extends RuntimeException
{
    /**
     * Name of the affected file path.
     *
     * @var string
     */
    protected $invalidPath;

    /**
     * Set the affected file path.
     *
     * @param  string   $path
     * @return $this
     */
    public function setInvalidPath($path)
    {
        $this->invalidPath = $path;

        $this->message = "Error creating file [{$path}]. Please check write permissions.";

        return $this;
    }

    /**
     * Get the affected file path.
     *
     * @return string
     */
    public function getInvalidPath()
    {
        return $this->invalidPath;
    }
}
