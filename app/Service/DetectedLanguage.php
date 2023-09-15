<?php
namespace App\Service;

use GuzzleHttp\Client;

class DetectedLanguage
{
    public static ?string $uri = "http://localhost:9998/language/string";

    public static function detected($text): string
    {
        $client = new Client();
        $response =  $client->request('PUT', self::$uri, [
            'form_params' => [
                'data' => $text
            ]
        ]);

        return $response->getBody()->getContents();
    }
}
