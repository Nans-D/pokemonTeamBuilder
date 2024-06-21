<?php



require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;

set_time_limit(300); // Augmente la limite d'exécution à 300 secondes

$client = new Client();
$pokemonPhotos = [];
$tempFile = 'pokemon_data.json';

// Vérifier si le fichier temporaire existe
if (file_exists($tempFile)) {
    // Lire les données depuis le fichier temporaire
    $data = json_decode(file_get_contents($tempFile), true);

    // Vérifier si les clés existent dans les données
    if (isset($data['pokemonList']) && isset($data['pokemonPhotos'])) {
        $pokemonList = $data['pokemonList'];
        $pokemonPhotos = $data['pokemonPhotos'];
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
        } else {
            // Gérer les erreurs si nécessaire
            $pokemonPhotos[$name] = null;
        }
    }

    // Enregistrer les données dans le fichier temporaire
    file_put_contents($tempFile, json_encode([
        'pokemonList' => $pokemonList,
        'pokemonPhotos' => $pokemonPhotos
    ]));
}

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
    </style>
</head>

<body class="overflow-xl" style="height:100vh;">
    <?php include('header.php') ?>

    <div class="container-xl">
        <section class="border border-danger my-2 p-4">
            <div class="row justify-content-center">
                <?php for ($i = 0; $i < 6; $i++) : ?>
                    <div class="col-6 col-md-4 col-xl-2 d-flex justify-content-center align-items-center">
                        <div class="rounded-5" style="aspect-ratio:2.80/4;height:250px;background: rgb(13,21,32);background: linear-gradient(331deg, rgba(13,21,32,1) 0%, rgba(0,51,98,1) 100%);">
                            <div class="" style="height:80%;">
                                <img data-card="<?= $i ?>" class="object-fit-contain build-card" style="width:100%;" src="" alt="">
                            </div>
                            <div class="text-light text-center pt-2">Pikachu</div>
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
                            <img src="<?php echo htmlspecialchars($pokemonPhotos[$pokemon['name']]); ?>" alt="<?php echo htmlspecialchars($pokemon['name']); ?>" class="pokemon-img img-fluid">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php require_once('./modalPokemon.php') ?>
    <script>
        $(document).ready(function() {
            $('[data-bs-toggle="popover"]').popover({
                trigger: 'hover'
            });

            $('.pokemon-img').on('click', function() {
                let src = $(this).attr('src')
                $('[data-card="0"]').attr("src", src);

            })
        })
    </script>
</body>

</html>