<?php

namespace Idez\Caradhras\Data;

use Psr\Http\Message\ResponseInterface;

abstract class Data implements \JsonSerializable
{
    protected ResponseInterface|array $attributes = [];

    public function __construct(object|array $data = [])
    {
        if ($data instanceof ResponseInterface) {
            $data = $this->toJson($data, true);
        }

        foreach ($data as $key => $val) {
            if (property_exists(static::class, $key)) {
                $this->$key = $val;

                continue;
            }

            $this->attributes[$key] = $val;
        }
    }

    public function toJson(ResponseInterface $response, bool $assoc = false): mixed
    {
        $contents = $response->getBody()->getContents();

        return json_decode($contents, $assoc);
    }

    /**
     * Set a value
     */
    public function __set(string $key, mixed $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Get a value
     *
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Check if a key is set
     *
     * @return bool
     */
    public function __isset(string $key)
    {
        return isset($this->attributes[$key]);
    }

    public function toArray(): array
    {
        $vars = [...get_object_vars($this), ...$this->getAttributes()];
        $array = [];

        foreach ($vars as $key => $var) {
            if (is_array($var) && $key == 'attributes') {
                continue;
            }

            if (is_object($var) && enum_exists($var::class)) {
                $var = $var->value;
            }

            $array[$key] = $var;
        }

        return $array;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return $this->attributes;
    }
}
