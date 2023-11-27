<?php

namespace Nikitinuser\YandexArtistsSearch\Services;

use Nikitinuser\YandexArtistsSearch\YaApi\BaseApi;

class Genres
{
    public const ROUTE = '/handlers/main.jsx?what=genres';

    private BaseApi $yaAPI;

    public function __construct()
    {
        $this->yaAPI = new BaseApi();
    }

    /**
     * @return string[]
     * 
     * @throws \GuzzleHttp\Exception\ClientException
     */
    public function getGenres(): array
    {
        $responseData = $this->yaAPI->getRequest(self::ROUTE);
        $responseData = $responseData['metatags'] ?? [];

        $leaves = [];
        for ($i = 0; $i < count($responseData); $i++) {
            if ($responseData[$i]['navigationId'] === 'genres') {
                $leaves = $responseData[$i]['leaves'];
                break;
            }
        }
        return $this->getAllTags($leaves);;
    }

    /**
     * @param array $searchArray
     * 
     * @return string[]
     */
    private function getAllTags(array $searchArray): array
    {
        $tags = [];
    
        foreach ($searchArray as $item) {
            if (isset($item['tag'])) {
                $tags[] = $item['tag'];
            }
    
            if (isset($item['leaves']) && is_array($item['leaves'])) {
                $tags = array_merge($tags, $this->getAllTags($item['leaves']));
            }
        }
    
        return $tags;
    }
}
