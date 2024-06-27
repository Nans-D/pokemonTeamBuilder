<?php
session_start(); // Démarrer la session au début

require_once './api/class/user.php'; // Assurez-vous que le chemin est correct
require_once 'config.php';

if (isset($_SESSION['name']) && $_SESSION['name'] != null) {
    $user = new User(null, $_SESSION['name'], null, null);
    $_SESSION['name'] = $user->getName();
} else {
    $_SESSION['name'] = null;
}

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
    "water" => 'background: linear-gradient(331deg, rgba(16,77,135,1) 0%, rgba(16,77,135,1) 100%);',
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
    "electric,steel" => 'background: linear-gradient(331deg, rgba(168,168,168,1) 0%, rgba(194,156,5,1) 100%);',
    "water,ice" => 'background: linear-gradient(331deg, rgba(124,226,254,1) 0%, rgba(16,77,135,1) 100%);',
    "ghost,poison" => 'background: linear-gradient(331deg, rgba(25,25,25,1) 0%, rgba(84,52,107,1) 100%);',
    "grass,psychic" => 'background: linear-gradient(331deg, rgba(23,73,51,1) 0%, rgba(105,41,85,1) 100%);',
    "psychic,fairy" => 'background: linear-gradient(331deg, rgba(255,141,204,1) 0%, rgba(105,41,85,1) 100%);',
    "ice,psychic" => 'background: linear-gradient(331deg, rgba(105,41,85,1) 0%, rgba(124,226,254,1) 100%);',
    "water,flying" => 'background: linear-gradient(331deg, rgba(16,77,135,1) 0%, rgba(194,230,255,1) 100%);',
    "rock,flying" => 'background: linear-gradient(331deg, rgba(194,230,255,1) 0%, rgba(173,127,88,1) 100%);',
    "ice,flying" => 'background: linear-gradient(331deg, rgba(124,226,254,1) 0%, rgba(194,230,255,1) 100%);',
    "electric,flying" => 'background: linear-gradient(331deg, rgba(124,226,254,1) 0%, rgba(194,156,5,1) 100%);',
    "rock,water" => 'background: linear-gradient(331deg, rgba(16,77,135,1) 0%, rgba(173,127,88,1) 100%);',
    "fire,flying" => 'background: linear-gradient(331deg, rgba(124,226,254,1) 0%, rgba(167,81,10,1) 100%);',
    "dragon,flying" => 'background: linear-gradient(331deg, rgba(124,226,254,1) 0%, rgba(27,83,123,1) 100%);',

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
        .disabled {
            pointer-events: none;
            opacity: 0.2;
        }

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
            aspect-ratio: 1/1;
        }

        .card-size {
            aspect-ratio: 2.80/4;
            height: 250px;
        }

        .background-card {
            background: linear-gradient(331deg, rgba(13, 21, 32, 1) 0%, rgba(0, 51, 98, 1) 100%);
        }



        @media(max-width:374.98px) {
            .card-size {
                height: 180px;
            }
        }

        @media(min-width:375px) and (max-width:575.98px) {
            .card-size {
                height: 220px;
            }
        }

        @media(min-width:576px) and (max-width:767.98px) {
            .card-size {
                height: 240px;
            }
        }

        @media(min-width:768px) and (max-width:991.98px) {
            .card-size {
                height: 170px;
            }
        }

        @media(min-width:992px) and (max-width:1139.98px) {
            .card-size {
                height: 220px;
            }
        }
    </style>
</head>

<header class="py-3">
    <div class="container-lg row justify-content-around mx-auto ">
        <div class="col align-self-center">
            <img src="./assets/img/International_Pokémon_logo.svg.png" alt="" style="width:150px;">
        </div>

        <div id="nav-bar-button" class="col text-end align-self-center position-relative">
            <div style="cursor:pointer;"><img src="./assets/img/pokeball.png" style="width:30px;" alt=""></div>
            <div id="nav-bar" class="position-absolute d-none end-0 bg-light rounded-4" style="width:150px;top:50px; background: radial-gradient(circle, rgba(112,184,255,1) 0%, rgba(16,77,135,1) 100%);">
                <ul class="d-flex flex-column justify-content-center align-items-center list-unstyled h-100 m-0">
                    <li class="text-center">
                        <a class="link-header text-light text-decoration-none fs-3" href="#">My team</a>
                    </li>
                    <li class="text-center">
                        <a class="link-header text-light text-decoration-none fs-3" method="post" href="<?= ($_SESSION['name'] != null) ? './api/logout.php' : './login.php'; ?>"><?= ($_SESSION['name'] != null) ? 'Logout' : 'Login'; ?></a>
                    </li>

                </ul>

            </div>
        </div>
    </div>
</header>

<script>
    $(document).ready(function() {
        $('.link-header').on('mouseenter', function() {
            $(this).addClass('border-bottom')
        })
        $('.link-header').on('mouseleave', function() {
            $(this).removeClass('border-bottom');
        })

        $('#nav-bar-button').on('click', function() {
            $('#nav-bar').toggleClass('d-block d-none');

        })
    })
</script>