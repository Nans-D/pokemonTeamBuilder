<?php

use PokePHP\PokeApi;

use function PokePHP\getFirstGeneration;

include './vendor/danrovito/pokephp/src/PokeApi.php';

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;

$pokemonApi = new PokeApi;

set_time_limit(300);

$client = new Client();
$pokemonPhotos = [];
$pokemonTypes = [];
$pokemonList = [];
$tempFile = 'pokemon_data.json';

if (file_exists($tempFile)) {
    // Lire les données depuis le fichier temporaire
    $data = json_decode(file_get_contents($tempFile));

    // Vérifier si les clés existent dans les données
    if (isset($data->pokemonList) && isset($data->pokemonPhotos) && isset($data->pokemonTypes)) {
        $pokemonList = $data->pokemonList;
        $pokemonPhotos = (array) $data->pokemonPhotos;
        $pokemonTypes = (array) $data->pokemonTypes;
    }
} else {
    // Récupérer les premiers 151 Pokémon
    $pokemonList = getFirstGeneration('pokemon', 151);

    $promises = [];
    foreach ($pokemonList as $pokemon) {
        $url = $pokemon->url;
        $promises[$pokemon->name] = $client->getAsync($url);
    }

    // Utilisation de Utils::settle pour gérer les promesses
    $responses = Utils::settle($promises)->wait();

    foreach ($responses as $name => $response) {
        if ($response['state'] === 'fulfilled') {
            $pokemonData = json_decode($response['value']->getBody()->getContents());
            $pokemonPhotos[$name] = $pokemonData->sprites->other->{'official-artwork'}->front_default;
            // Récupérer les types de Pokémon
            $types = array_map(function ($typeInfo) {
                return $typeInfo->type->name;
            }, $pokemonData->types);
            $pokemonTypes[$name] = $types;
        } else {
            // Gérer les erreurs si nécessaire
            $pokemonPhotos[$name] = null;
            $pokemonTypes[$name] = null;
        }
    }

    // Enregistrer les données dans le fichier temporaire
    file_put_contents($tempFile, json_encode([
        'pokemonList' => $pokemonList,
        'pokemonPhotos' => $pokemonPhotos,
        'pokemonTypes' => $pokemonTypes
    ]));
}

// Couleurs pour les types de Pokémon
define('TYPE_COLOR', [
    "normal" => 'background: linear-gradient(331deg, rgba(58,58,58,1) 0%, rgba(58,58,58,1) 100%);',
    "fire" => 'background: linear-gradient(331deg, rgba(245,172,120,1) 0%, rgba(245,172,120,1) 100%);',
    "water" => 'background: linear-gradient(331deg, rgba(157,183,245,1) 0%, rgba(157,183,245,1) 100%);',
    "flying" => 'background: linear-gradient(331deg, rgba(194,230,255,1) 0%, rgba(194,230,255,1) 100%);',
    "bug" => 'background: linear-gradient(331deg, rgba(51,68,35,1) 0%, rgba(51,68,35,1) 100%);',
    "poison" => 'background: linear-gradient(331deg, rgba(84,52,107,1) 0%, rgba(84,52,107,1) 100%);',
    "electric" => 'background: linear-gradient(331deg, rgba(194,156,5,1) 0%, rgba(194,156,5,1) 100%);',
    "ghost" => 'background: linear-gradient(331deg, rgba(25,25,25,1) 0%, rgba(25,25,25,1) 100%);',
    "dragon" => 'background: linear-gradient(331deg, rgba(27,83,123,1) 0%, rgba(27,83,123,1) 100%);',
    "ground" => 'background: linear-gradient(331deg, rgba(62,49,40,1) 0%, rgba(62,49,40,1) 100%);',
    "rock" => 'background: linear-gradient(331deg, rgba(173,127,88,1) 0%, rgba(173,127,88,1) 100%);',
    "grass" => 'background: linear-gradient(331deg, rgba(23,73,51,1) 0%, rgba(23,73,51,1) 100%);',
    "ice" => 'background: linear-gradient(331deg, rgba(124,226,254,1) 0%, rgba(124,226,254,1) 100%);',
    "psychic" => 'background: linear-gradient(331deg, rgba(105,41,85,1) 0%, rgba(105,41,85,1) 100%);',
    "fairy" => 'background: linear-gradient(331deg, rgba(255,141,204,1) 0%, rgba(255,141,204,1) 100%);',
    "fighting" => 'background: linear-gradient(331deg, rgba(110,41,32,1) 0%, rgba(110,41,32,1) 100%);',
    "grass,poison" => 'background: linear-gradient(331deg, rgba(23,73,51,1) 0%, rgba(84,52,107,1) 100%);',
    "normal,flying" => 'background: linear-gradient(331deg, rgba(58,58,58,1) 0%, rgba(194,230,255,1) 100%);',
    "poison,flying" => 'background: linear-gradient(331deg, rgba(84,52,107,1) 0%, rgba(194,230,255,1) 100%);',
    "fire,flying" => 'background: linear-gradient(331deg, rgba(198,183,245,1) 0%, rgba(245,172,120,1) 100%);',
    "bug,flying" => 'background: linear-gradient(331deg, rgba(198,183,245,1) 0%, rgba(198,209,110,1) 100%);',
    "bug,poison" => 'background: linear-gradient(331deg, rgba(193,131,193,1) 0%, rgba(198,209,110,1) 100%);',
    "poison,ground" => 'background: linear-gradient(331deg, rgba(84,52,107,1) 0%, rgba(62,49,40,1) 100%);',
    "normal,fairy" => 'background: linear-gradient(331deg, rgba(58,58,58,1) 0%, rgba(255,141,204,1) 100%);',
    "bug,grass" => 'background: linear-gradient(331deg, rgba(51,68,35,1) 0%, rgba(23,73,51,1) 100%);',
    "water,fighting" => 'background: linear-gradient(331deg, rgba(110,41,32,1) 0%, rgba(16,77,135,1) 100%);',
    "water,poison" => 'background: linear-gradient(331deg, rgba(84,52,107,1) 0%, rgba(16,77,135,1) 100%);',
    "water,psychic" => 'background: linear-gradient(331deg, rgba(105,41,85,1) 0%, rgba(16,77,135,1) 100%);',
    "rock,ground" => 'background: linear-gradient(331deg, rgba(62,49,40,1) 0%, rgba(173,127,88,1) 100%);',

    "rock,ground" => 'background: linear-gradient(331deg, rgba(62,49,40,1) 0%, rgba(173,127,88,1) 100%);',






    // Ajoutez d'autres types ou combinaisons ici...
]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Gallery</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/6a8041b5d6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./assets/style.css">

    <style>
        .pokemon-card {
            margin: 10px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .pokemon-img {
            width: 100px;
            height: 100px;
        }

        .card-size {
            aspect-ratio: 2.80/4;
            height: 250px;
        }

        .background-card {
            background: linear-gradient(331deg, rgba(13, 21, 32, 1) 0%, rgba(0, 51, 98, 1) 100%);
        }
    </style>
</head>

<body class="overflow-xl" style="height:100vh;">
    <?php include('header.php') ?>

    <div class="container-xl">
        <section class="border border-danger my-2 p-4">
            <div class="row justify-content-center">
                <?php for ($i = 0; $i < 6; $i++) : ?>
                    <div class="col-6 col-md-4 col-xl-2 d-flex justify-content-center align-items-center">
                        <div data-card="<?= $i ?>" class="rounded-5 card-size background-card">
                            <div class="d-flex justify-content-center align-items-center" style="height:80%;">
                                <img class="object-fit-contain build-card" style="width:80%;" src="" alt="">
                            </div>
                            <div data-name class="text-light text-center pt-2">???</div>
                        </div>
                    </div>
                <?php endfor ?>
            </div>
        </section>
        <div class="container">
            <div class="row justify-content-center">
                <?php foreach ($pokemonList as $pokemon) : ?>
                    <?php if (isset($pokemonPhotos[$pokemon->name])) : ?>
                        <div class="col-auto pokemon-card background-card" data-bs-toggle="popover" data-bs-content="<?= htmlspecialchars(ucfirst($pokemon->name)); ?>" data-bs-placement="top" data-type="<?= htmlspecialchars(implode(',', $pokemonTypes[$pokemon->name])); ?>">
                            <img data-name="<?= $pokemon->name ?>" src="<?= htmlspecialchars($pokemonPhotos[$pokemon->name]); ?>" alt="<?= htmlspecialchars($pokemon->name); ?>" class="pokemon-img img-fluid">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

            </div>
        </div>
    </div>

    <?php require_once('./modalPokemon.php') ?>
    <script type="text/javascript">
        const TYPE_COLOR = <?= json_encode(TYPE_COLOR); ?>;

        $(document).ready(function() {
            let dataCardArray = [];
            for (let i = 0; i <= 5; i++) {
                dataCardArray.push($(`[data-card='${i}']`));
            }

            $('[data-bs-toggle="popover"]').popover({
                trigger: 'hover'
            });

            $('.pokemon-img').on('click', function() {
                let src = $(this).attr('src');
                let name = $(this).data('name');
                let types = $(this).closest('.pokemon-card').data('type');
                console.log(types);
                let capitalizedName = name.charAt(0).toUpperCase() + name.slice(1);

                for (let i = 0; i < dataCardArray.length; i++) {
                    if (!dataCardArray[i].find('img').attr('src')) {
                        dataCardArray[i].find('img').attr('src', src);
                        dataCardArray[i].find('[data-name]').text(capitalizedName);
                        dataCardArray[i].removeClass('background-card');

                        if (TYPE_COLOR[types]) {
                            dataCardArray[i].attr('style', TYPE_COLOR[types]);
                        }

                        break; // Sort de la boucle après avoir trouvé le premier élément vide
                    }
                }
            });

            dataCardArray.forEach((element) => {
                element.on('click', function() {
                    element.find('img').attr('src', "");
                    element.find('[data-name]').text("???");
                    element.removeAttr('style');
                    element.addClass('background-card');
                });
            });
        });
    </script>

</body>

</html>