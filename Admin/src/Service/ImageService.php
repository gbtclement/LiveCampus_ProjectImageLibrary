<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImageService
{
    public function __construct(
        private HttpClientInterface $client
    ) {}

    public function fetchImages(): array
    {
        $response = $this->client->request('GET', 'https://localhost:8002/api/images/stats', [
            'verify_peer' => false,
            'verify_host' => false,
        ]);

        return $response->toArray();
    }
}
