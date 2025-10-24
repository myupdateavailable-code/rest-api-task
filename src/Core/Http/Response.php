<?php

declare(strict_types=1);

namespace App\Core\Http;

class Response
{

    public function json(array $data = null)
    {
        http_response_code($data['code'] ?? 200);

        header('Content-Type: application/json');

        if (isset($data['headers'])) {
            foreach ($data['headers'] as $header) {
                header($header);
            }
        }

        echo json_encode($data['payload']);
    }

    public function error(int $status = 500, array $data = [])
    {
        http_response_code($status);

        if (!empty($data)) {
            header('Content-Type: application/json');
            echo json_encode($data);
        }

        // we can use exceptions or logger instead exit
        exit();
    }

}