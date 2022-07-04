<?php

namespace Idez\Caradhras\Adapters;

class Fingerprint
{
    /**
     * @param  string  $userAgent
     * @param  string  $ip
     */
    public function __construct(private string $userAgent, private string $ip)
    {
    }

    public function tokenize(): string
    {
        return "{$this->userAgent}#{$this->ip}";
    }
}
