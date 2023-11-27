<?php

namespace Nikitinuser\YandexArtistsSearch;

use Nikitinuser\YandexArtistsSearch\Services\Genres;
use Nikitinuser\YandexArtistsSearch\Services\Albums;
use Nikitinuser\YandexArtistsSearch\Services\Artists;

class YASearch
{
    private Genres $genres;
    private Albums $albums;
    private Artists $artists;

    public function __construct()
    {
        $this->genres = new Genres();
        $this->albums = new Albums();
        $this->artists = new Artists();
    }

    /**
     * @return string[]
     * 
     * @throws \GuzzleHttp\Exception\ClientException
     */
    public function getGenres(): array
    {
        return $this->genres->getGenres();
    }

    /**
     * @param string $genre
     * @param bool|null $ruName - default null
     * @param int|null $minAlbumYear - default null Год выхода альбом должен быть больше
     * @param int|null $maxAlbumYear - default null Год выхода альбом должен быть меньше
     * 
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
    ) {
        return $this->albums->getArtistsByAlbumGenre(
            $genre,
            $ruName,
            $minAlbumYear,
            $maxAlbumYear
        );
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
        return $this->artists->getArtistById($artistId);
    }
}
