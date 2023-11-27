<?php

namespace Nikitinuser\YandexArtistsSearch\Services;

use Nikitinuser\YandexArtistsSearch\YaApi\BaseApi;
use GuzzleHttp\Exception\ClientException;

class Albums
{
    public const ROUTE = '/handlers/metatag.jsx?id=%s&tab=albums&page=%d';

    /**
     * после этой страницы выдает 400
     */
    public const MAX_PAGE = 100;

    private BaseApi $yaAPI;

    public function __construct()
    {
        $this->yaAPI = new BaseApi();
    }

    /**
     * @param string $genre
     * @param bool|null $ruName - default null
     * @param int|null $minAlbumYear - default null Год выхода альбом должен быть больше
     * @param int|null $maxAlbumYear - default null Год выхода альбом должен быть меньше
     * 
     * @return array - [
     *      [
     *          'id' => 1 // yandex artist id
     *          'name' => '' // yandex artist name
     *          'year' => 2008 // album year
     *      ]
     * ]
     */
    public function getArtistsByAlbumGenre(
        string $genre,
        ?bool $ruName = null,
        ?int $minAlbumYear = null,
        ?int $maxAlbumYear = null
    ): array {
        $albumsData = [];

        for ($i = 0; $i < self::MAX_PAGE; $i++) {
            try {
                $route = sprintf(self::ROUTE, $genre, $i);
                $responseData = $this->yaAPI->getRequest($route);
                $responseData = $responseData['metatag']['albums'] ?? [];

                if (empty($responseData)) {
                    break;
                }

                if (!is_null($minAlbumYear)) {
                    $firstAlbum = $responseData[0];
                    if (!$this->checkMinYear($firstAlbum, $minAlbumYear)) {
                        break;
                    }
                }

                if (!is_null($maxAlbumYear)) {
                    $lastAlbum = $responseData[count($responseData) - 1];
                    if (!$this->checkMaxYear($lastAlbum, $maxAlbumYear)) {
                        continue;
                    }
                }

                $artists = $this->getArtist($responseData, $ruName);
                $albumsData = array_merge($albumsData, $artists);
            } catch (ClientException $clientException) {
                break;
            }
        }
        
        return $albumsData;
    }

    /**
     * @param array $lastAlbumOnPage
     * @param int $maxYear
     * 
     * @return bool
     */
    private function checkMaxYear(array $lastAlbumOnPage, int $maxYear): bool
    {
        if (empty($lastAlbumOnPage['year'] ?? null)) {
            return false;
        }

        return $lastAlbumOnPage['year'] <= $maxYear;
    }

    /**
     * @param array $firstAlbumOnPage
     * @param int $minYear
     * 
     * @return bool
     */
    private function checkMinYear(array $firstAlbumOnPage, int $minYear): bool
    {
        if (empty($firstAlbumOnPage['year'] ?? null)) {
            return false;
        }

        return $firstAlbumOnPage['year'] >= $minYear;
    }

    /**
     * @param array $albums,
     * @param bool|null $ruName - default null
     * 
     * @return array - [
     *      [
     *          'id' => 1 // yandex artist id
     *          'name' => '' // yandex artist name
     *          'year' => 2008 // album year
     *      ]
     * ]
     */
    private function getArtist(
        array $albums,
        ?bool $ruName = null
    ): array {
        $data = [];

        for ($i = 0; $i < count($albums); $i++) {
            if (!isset($albums[$i]['artists'][0]['id'])) {
                continue;
            }

            $id = $albums[$i]['artists'][0]['id'];
            $name = $albums[$i]['artists'][0]['name'] ?? null;
            $year = $albums[$i]['year'] ?? null;

            if ($ruName === true && !preg_match('/[А-Яа-яЁё]/u', $name)) {
                continue;
            } elseif ($ruName === false && preg_match('/[А-Яа-яЁё]/u', $name)) {
                continue;
            }

            $data[] = [
                'id' => $id,
                'name' => $name,
                'year' => $year,
            ];
        }

        return $data;
    }
}
