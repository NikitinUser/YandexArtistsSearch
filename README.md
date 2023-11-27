# YandexArtistsSearch
## Библиотека для поиска по яндекс музыке
Позволяет получить:

1. Список жанров
2. Список артистов с альбомами в жанре
3. Получить информацию по артисту

## Установка
composer require nikitinuser/yandex-artists-search

## Использование
```
use Nikitinuser\YandexArtistsSearch\YASearch;
$search = new YASearch();

// получить массив жанров
$search->getGenres();

// получить артистов по жанру
$search->getArtistsByAlbumGenre('русский рок');

// получить артистов с русскими буквами в названии
$search->getArtistsByAlbumGenre('русский рок', ruName: true);

// получить артистов без русских букв в названии
$search->getArtistsByAlbumGenre('русский рок', ruName: false);

// получить артистов с альбомами в жанке с датой выхода позднее 2008
$search->getArtistsByAlbumGenre('русский рок', minAlbumYear: 2008);

// получить артистов с альбомами в жанке с датой выхода раньше 2020
$search->getArtistsByAlbumGenre('русский рок', maxAlbumYear: 2020);

// получить данные конкретного артиста по id
$search->getArtistById(1);
```
