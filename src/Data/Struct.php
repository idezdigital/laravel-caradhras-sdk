<?php

namespace Idez\Caradhras\Data;

use Psr\Http\Message\ResponseInterface;

abstract class Struct implements \JsonSerializable
{
    /**
     * Private internal struct attributes
     * @var array|ResponseInterface
     */
    protected $attributes = [];

    public function __construct($data = [])
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

    /**
     * @param ResponseInterface $response
     * @param bool $assoc
     * @return mixed
     */
    public function toJson(ResponseInterface $response, $assoc = false)
    {
        $contents = $response->getBody()->getContents();

        return json_decode($contents, $assoc);
    }

    /**
     * Set a value
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Get a value
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Check if a key is set
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]) ? true : false;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $vars = [...get_object_vars($this),  ...$this->getAttributes()];
        $array = [];

        foreach ($vars as $key => $var) {
            if (is_array($var) && $key === 'attributes') {
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
