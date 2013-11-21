<?php

namespace RestRemoteObject\Client\Rest\Debug\Writer;

class Printer implements WriterInterface
{
    public function write($text)
    {
        print $text;
    }
}
