<?php

namespace Sevaske\LaravelDiscourse\Contracts;

use ArrayAccess;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface that guarantees array-style access and JSON serialization.
 */
interface DiscourseResponseContract extends ArrayAccess, JsonSerializable
{
    /**
     * Returns the original raw PSR-7 HTTP response object OR array.
     *
     * @return ResponseInterface|array The raw response.
     */
    public function raw(): ResponseInterface|array;

    public function getHttpStatusCode(): ?int;
}
