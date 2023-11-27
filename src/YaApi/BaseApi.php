<?php

namespace Nikitinuser\YandexArtistsSearch\YaApi;

use GuzzleHttp\Client;

class BaseApi
{
    public const BASE_URI = 'https://music.yandex.ru';

    protected Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => self::BASE_URI,
        ]);
    }

    /**
     * @param string $route
     * 
     * @throws \GuzzleHttp\Exception\ClientException
     */
    public function getRequest(string $route): array
    {
        $response = $this->httpClient->request('GET', $route);

        $data = $response->getBody()->getContents();
        $data = json_decode($data, true);

        return is_array($data) ? $data : [];
    }
}
