<?php

namespace Yousef\GenerateDoc\Files;

abstract class TemporaryFile
{
    /**
     * @return string
     */
    abstract public function getLocalPath(): string;

    /**
     * @return bool
     */
    abstract public function exists(): bool;

    /**
     * @param @param string|resource $contents
     */
    abstract public function put($contents);

    /**
     * @return bool
     */
    abstract public function delete(): bool;

    /**
     * @return resource
     */
    abstract public function readStream();

    /**
     * @return string
     */
    abstract public function contents(): string;

    /**
     * @return TemporaryFile
     */
    public function sync(): TemporaryFile
    {
        return $this;
    }

    abstract public function download();
  
}
