<?php

declare(strict_types=1);

namespace App\DTO;

class RequestDTO
{

    private string $method;

    private array $headers;

    private array $get;

    private array $post;

    private string $json;

    public function __construct(
        string $method,
        array $headers,
        array $get,
        array $post,
        string $json,
    ) {
        $this->method = $method;
        $this->headers = $headers;
        $this->get = $get;
        $this->post = $post;
        $this->json = $json;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function header(string $key = null): ?array
    {
        if (null === $key) {
            return $this->headers;
        }
        return [$key => $this->headers[$key] ?? null];
    }

    public function get(string $key = null): ?array
    {
        if (null === $key) {
            return $this->get;
        }
        return [$key => $this->get[$key] ?? null];
    }

    public function post(string $key = null): ?array
    {
        if (null === $key) {
            return $this->post;
        }
        return [$key => $this->post[$key] ?? null];
    }

    public function json(): string
    {
        return $this->json;
    }

}