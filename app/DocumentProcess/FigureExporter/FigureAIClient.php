<?php

namespace App\DocumentProcess\FigureExporter;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;

final class FigureAIClient
{
    protected string $host;

    protected string $token;

    protected Client $client;

    /**
     * ClientAbstract constructor.
     *
     * @param string|null $host
     * @param string|null $token
     */
    public function __construct(string $host = null, string $token = null)
    {
        $this->host = $host ?: config('doc_services.ai_figure_detector.host');
        $this->token = $token ?: config('doc_services.ai_figure_detector.token');
        $this->client = new Client([
            'base_uri' => $this->host,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
            ],
            'timeout' => 60,
            'verify' => false,
        ]);
    }

    /**
     * @return bool
     */
    public function ping(): bool {
        try {
            $response = $this->client->get('');
            return $response->getStatusCode() == 200;
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return false;
        }
    }

    /**
     * @param mixed $image_content
     *
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function detect(mixed $image_content) {
        $response = $this->client->post('/detect', [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => $image_content,
                    'filename' => 'page.png',
                ],
            ]
        ])->getBody()->getContents();
        $response = json_decode($response, true);
        $collection = collect();

        foreach ($response['detected'] as $item) {
            if (in_array($item['type'], ['Table', 'Figure'])) {
                $collection->push(Entities\Figure::create($item));
            }
        }

        return $collection->sort(function (Entities\Figure $figure1, Entities\Figure $figure2) {
            return ($figure1->position['x-top'] > $figure2->position['x-top']) ? 1 : -1; //từ trái sang phải
        })->values()
            ->sort(function (Entities\Figure $figure1, Entities\Figure $figure2) {
                return ($figure1->position['y-top'] > $figure2->position['y-top'] - 20) ? 1 : -1; //từ trên xuống dưới (sai số 20px)
            })->values();
    }
}