<?php

namespace RestRemoteObject\Client\Rest\Debug\Writer;

class Stdout implements WriterInterface
{
    public function write($text)
    {
        $stdout = fopen('php://stdout', 'w');
        fwrite($stdout, $text);
        fclose($stdout);
    }
}
