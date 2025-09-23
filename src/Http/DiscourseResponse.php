<?php

namespace Sevaske\LaravelDiscourse\Http;

use Psr\Http\Message\ResponseInterface;
use Sevaske\Discourse\Exceptions\DiscourseException;
use Sevaske\LaravelDiscourse\Contracts\DiscourseResponseContract;
use Sevaske\LaravelDiscourse\Traits\HasAttributes;

class DiscourseResponse implements DiscourseResponseContract
{
    use HasAttributes;

    public function __construct(protected ?ResponseInterface $rawResponse, protected ?int $httpStatusCode = null)
    {
        if ($this->rawResponse instanceof ResponseInterface) {
            $this->attributes = self::parse($this->rawResponse);
            $this->httpStatusCode = $this->httpStatusCode ?: $this->rawResponse->getStatusCode();
        } else {
            $this->attributes = $this->response;
        }
    }

    public function raw(): ResponseInterface|array
    {
        return $this->rawResponse;
    }

    /**
     * Parses the PSR-7 response and returns an associative array of its JSON contents.
     *
     * @param  ResponseInterface  $response  The HTTP response to parse.
     * @return array The decoded JSON content as an array.
     *
     * @throws DiscourseException If JSON decoding fails.
     */
    public static function parse(ResponseInterface $response): array
    {
        $body = $response->getBody();

        if ($body->isSeekable()) {
            $body->rewind();
        }

        $content = $body->getContents();
        $parsed = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new DiscourseException(json_last_error_msg(), [
                'content' => $content,
                'status' => $response->getStatusCode(),
            ]);
        }

        return (array) $parsed;
    }

    public function getHttpStatusCode(): ?int
    {
        return $this->httpStatusCode;
    }
}
