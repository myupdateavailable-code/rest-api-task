<?php

declare(strict_types=1);

namespace App\Core\Http;

class Request
{

    public array $get;

    public array $post;

    public array $headers;

    public ?array $json;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->headers = getallheaders();
        $this->json = $this->parseJson();
    }

    private function parseJson(): ?array
    {
        $raw = file_get_contents('php://input');
        if (!$raw) {
            return null;
        }
        $data = json_decode($raw, true);
        return is_array($data) ? $data : null;
    }
}