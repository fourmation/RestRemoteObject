<?php

namespace RestRemoteObject\Client\Rest\Debug;

use RestRemoteObject\Client\Rest\Debug\Verbosity\Verbosity;
use RestRemoteObject\Client\Rest\Debug\Writer\WriterInterface;
use RestRemoteObject\Client\Rest\Debug\Writer\Null;

class Debug
{
    /**
     * @var Verbosity
     */
    protected $verbosity;

    /**
     * @var WriterInterface
     */
    protected $writer;

    /**
     * @return Verbosity
     */
    public function getVerbosity()
    {
        if (null === $this->verbosity) {
            $this->setVerbosity(new Verbosity(Verbosity::TRACE_DISABLED));
        }

        return $this->verbosity;
    }

    /**
     * @param Verbosity $verbosity
     */
    public function setVerbosity(Verbosity $verbosity)
    {
        $this->verbosity = $verbosity;
    }

    /**
     * @return WriterInterface
     */
    public function getWriter()
    {
        if (null === $this->writer) {
            $this->setWriter(new Null());
        }

        return $this->writer;
    }

    /**
     * @param WriterInterface $writer
     */
    public function setWriter(WriterInterface $writer)
    {
        $this->writer = $writer;
    }
}
