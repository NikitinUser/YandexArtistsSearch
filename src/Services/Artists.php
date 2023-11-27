<?php

namespace Nikitinuser\YandexArtistsSearch\Services;

use Nikitinuser\YandexArtistsSearch\YaApi\BaseApi;
use GuzzleHttp\Exception\ClientException;

class Artists
{
    public const ROUTE = '/handlers/artist.jsx?artist=%d';

    private BaseApi $yaAPI;

    public function __construct()
    {
        $this->yaAPI = new BaseApi();
    }

    /**
     * @param int $artistId
     * 
     * @return array [
     *      'id' => 1,
     *      'name' => '', // must be null
     *      'genres' => [], // must be null
     *      'lastMonthListeners' => 0, // must be null
     * ]
     */
    public function getArtistById(int $artistId): array
    {
        $route = sprintf(self::ROUTE, $artistId);
        $responseData = $this->yaAPI->getRequest($route);
        return [
            'id' => $artistId,
            'name' => ($responseData['artist']['name'] ?? null),
            'genres' => ($responseData['artist']['genres'] ?? null),
            'lastMonthListeners' => ($responseData['stats']['lastMonthListeners'] ?? null),
        ];
    }
}