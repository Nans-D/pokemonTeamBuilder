<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;

set_time_limit(300); // Augmente la limite d'exécution à 300 secondes

$client = new Client();
$pokemonPhotos = [];
$pokemonTypes = [];
$pokemonList = [];
$tempFile = 'pokemon_data.json';

// Vérifier si le fichier temporaire existe
if (file_exists($tempFile)) {
    // Lire les données depuis le fichier temporaire
    $data = json_decode(file_get_contents($tempFile), true);

    // Vérifier si les clés existent dans les données
    if (isset($data['pokemonList']) && isset($data['pokemonPhotos']) && isset($data['pokemonTypes'])) {
        $pokemonList = $data['pokemonList'];
        $pokemonPhotos = $data['pokemonPhotos'];
        $pokemonTypes = $data['pokemonTypes'];
    }
} else {
    // Récupérer les premiers 151 Pokémon en une seule requête
    $response = $client->get('https://pokeapi.co/api/v2/pokemon?limit=151');
    $pokemonList = json_decode(json_encode(json_decode($response->getBody())->results), true);

    $promises = [];
    foreach ($pokemonList as $pokemon) {
        $url = $pokemon['url'];
        $promises[$pokemon['name']] = $client->getAsync($url);
    }

    // Utilisation de Utils::settle pour gérer les promesses
    $responses = Utils::settle($promises)->wait();

    foreach ($responses as $name => $response) {
        if ($response['state'] === 'fulfilled') {
            $pokemonData = json_decode($response['value']->getBody());
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

const TYPE_COLOR = [
    'grass, poison' => [
        'background-color' => 'background: linear-gradient(331deg, rgba(108,40,179,1) 0%, rgba(0,98,2,1) 100%);
'
    ],
    'grass' => [
        'background-color' => 'test'
    ],

];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Gallery</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
                        <div data-card="<?= $i ?>" class="rounded-5 background-card" style="aspect-ratio:2.80/4;height:250px;">
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
                    <?php if (isset($pokemonPhotos[$pokemon['name']])) : ?>

                        <div class="col-auto pokemon-card" style="aspect-ratio:1/1;background: rgb(13,21,32);background: linear-gradient(331deg, rgba(13,21,32,1) 0%, rgba(0,51,98,1) 100%);" data-bs-toggle="popover" data-bs-content="<?php echo htmlspecialchars(ucfirst($pokemon['name'])); ?>" data-bs-placement="top">
                            <img data-name="<?= $pokemon['name'] ?>" src="<?php echo htmlspecialchars($pokemonPhotos[$pokemon['name']]); ?>" alt="<?php echo htmlspecialchars($pokemon['name']); ?>" class="pokemon-img img-fluid">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php require_once('./modalPokemon.php') ?>
    <script>
        $(document).ready(function() {

            let dataCardArray = [];
            for (let i = 0; i <= 5; i++) {
                dataCardArray.push($(`[data-card='${i}']`))
            }

            $('[data-bs-toggle="popover"]').popover({
                trigger: 'hover'
            });

            let pokemonArray = ['test'];

            $('.pokemon-img').on('click', function() {

                pokemonArray = [];
                pokemonArray.push($(this).data('name'));


                let name = pokemonArray.map(pokemon => {
                    let nameSplit = pokemon.split('');
                    let capitalizedLetter = nameSplit[0].toUpperCase();
                    let restOfName = nameSplit.slice(1).join('');
                    return capitalizedLetter + restOfName;
                });

                let src = $(this).attr('src');
                for (let i = 0; i < dataCardArray.length; i++) {
                    if (!dataCardArray[i].find('img').attr('src')) {
                        dataCardArray[i].find('img').attr('src', src)
                        dataCardArray[i].find('[data-name]').text(name)

                        break; // Sort de la boucle après avoir trouvé le premier élément vide
                    }
                }
            });

            dataCardArray.forEach((element) => {
                element.on('click', function() {

                    element.find('img').attr('src', "");
                    element.find('[data-name]').text("???")
                })


            });
            // $('[data-card]').on('click', function() {
            //     $(this).attr('src', "");
            // })
        })
    </script>
</body>

</html>