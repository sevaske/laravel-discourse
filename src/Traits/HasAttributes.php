<?php

namespace Sevaske\LaravelDiscourse\Traits;

use Sevaske\LaravelDiscourse\Exceptions\InvalidArgumentException;

trait HasAttributes
{
    /**
     * Internal storage for dynamic attributes.
     */
    protected array $attributes = [];

    /**
     * Magic method to retrieve the value of a dynamic attribute.
     *
     * @param  string  $name  The name of the attribute.
     * @return mixed|null The value of the attribute or null if not set.
     */
    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Magic method to set the value of a dynamic attribute.
     *
     * @param  string  $name  The name of the attribute.
     * @param  mixed  $value  The value to assign to the attribute.
     */
    public function __set(string $name, mixed $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Magic method to check if a dynamic attribute is set.
     *
     * @param  string  $name  The name of the attribute.
     * @return bool True if the attribute is set, false otherwise.
     */
    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    /**
     * Magic method to unset a dynamic attribute.
     *
     * @param  string  $name  The name of the attribute to unset.
     */
    public function __unset(string $name): void
    {
        unset($this->attributes[$name]);
    }

    /**
     * Retrieves an attribute value.
     *
     * @param  string  $key  The attribute key.
     * @return mixed The attribute value.
     *
     * @throws InvalidArgumentException
     */
    protected function getAttribute(string $key): mixed
    {
        if (! isset($this->attributes[$key])) {
            throw new InvalidArgumentException('Undefined attribute: '.$key);
        }

        return $this->attributes[$key] ?? null;
    }

    /**
     * Retrieves an attribute value or null if undefined.
     *
     * @param  string  $key  The attribute key.
     * @return mixed|null The attribute value or null if not found.
     */
    protected function getOptionalAttribute(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Checks whether the given offset exists in the internal attributes.
     *
     * @param  mixed  $offset  The attribute key.
     * @return bool True if set, false otherwise.
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * Retrieves a value by array key (offset).
     *
     * @param  mixed  $offset  The attribute key.
     * @return mixed|null The attribute value, or null if not set.
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->attributes[$offset] ?? null;
    }

    /**
     * Sets a value by array key (offset).
     *
     * @param  mixed  $offset  The attribute key.
     * @param  mixed  $value  The value to set.
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->attributes[$offset] = $value;
    }

    /**
     * Unsets a value by array key (offset).
     *
     * @param  mixed  $offset  The attribute key.
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Serializes the internal attributes to an array for JSON representation.
     *
     * @return array The internal attributes.
     */
    public function jsonSerialize(): array
    {
        return $this->attributes;
    }
}
