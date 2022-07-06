<?php

namespace Idez\Caradhras\Data;

/**
 * @property string $userAgent
 * @property string $ip
 */
class Fingerprint extends Data
{
    /**
     * @param  string  $userAgent
     * @param  string  $ip
     */
    public function __construct(string $userAgent, string $ip)
    {
        parent::__construct([
            'userAgent' => $userAgent,
            'ip' => $ip,
        ]);
    }

    public function tokenize(): string
    {
        return "{$this->userAgent}#{$this->ip}";
    }
}
