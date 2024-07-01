<?php

session_start(); // Démarrer la session au début

require_once './api/class/user.php'; // Assurez-vous que le chemin est correct  

if (isset($_SESSION['name']) && $_SESSION['name'] != null) {
    $user = new User($_SESSION['idUser'], $_SESSION['name'], $_SESSION['email']);
} else {
    $_SESSION['name'] = null;
    $_SESSION['idUser'] = null;
}


use function PokePHP\getFirstGeneration;

include './vendor/danrovito/pokephp/src/PokeApi.php';

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;


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

include('header.php') ?>

<body class="overflow-xl" style="height:100vh;">

    <div class="container-xl">
        <section class="my-2 p-4">
            <div class="row g-3 justify-content-center">
                <?php for ($i = 0; $i < 6; $i++) : ?>
                    <div class="col-6 col-sm-4 col-md-2 col-xl-2">
                        <div data-card="<?= $i ?>" class="rounded-5 card-size background-card mx-auto">
                            <div class="d-flex justify-content-center align-items-center" style="height:80%;">
                                <img class="object-fit-contain build-card" style="width:80%;" src="" alt="">
                            </div>
                            <div data-name class="text-light text-center pt-2">???</div>
                        </div>
                        <div class="d-flex justify-content-center pt-3">
                            <button data-name data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i ?>" class='btn btn-primary align-self-center'>Voir plus</button>
                        </div>
                    </div>
                <?php endfor ?>
            </div>
            <div class='d-flex justify-content-center'>

                <button id="save-team" class='mt-5 px-3 py-1 btn btn-primary'>Save</button>
            </div>
        </section>
        <section class="my-2 p-4">
            <div class="row justify-content-center">
                <?php foreach ($pokemonList as $pokemon) : ?>
                    <?php if (isset($pokemonPhotos[$pokemon->name])) : ?>
                        <div class="col-3 col-sm-2 col-md-auto pokemon-card background-card" data-bs-toggle="popover" data-bs-content="<?= htmlspecialchars(ucfirst($pokemon->name)); ?>" data-bs-placement="top" data-type="<?= htmlspecialchars(implode(',', $pokemonTypes[$pokemon->name])); ?>">
                            <img data-name="<?= $pokemon->name ?>" src="<?= htmlspecialchars($pokemonPhotos[$pokemon->name]); ?>" alt="<?= htmlspecialchars($pokemon->name); ?>" class="object-fit-contain pokemon-img img-fluid">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <?php require_once('./modalPokemon.php') ?>
    <script type="text/javascript">
        const TYPE_COLOR = <?= json_encode(TYPE_COLOR); ?>;

        $(document).ready(function() {
            let dataCardArray = [];
            for (let i = 0; i <= 5; i++) {
                dataCardArray.push($(`[data-card='${i}']`));
            }
            let dataModalArray = [];
            for (let i = 0; i <= 5; i++) {
                dataModalArray.push($(`[data-modal="${i}"]`));
            }

            checkBackgroundCard();

            $('[data-bs-toggle="popover"]').popover({
                trigger: 'hover'
            });


            $('.pokemon-img').on('click', function() {
                let src = $(this).attr('src');
                let name = $(this).data('name');
                let types = $(this).closest('.pokemon-card').data('type');
                let capitalizedName = name.charAt(0).toUpperCase() + name.slice(1);

                for (let i = 0; i < dataCardArray.length; i++) {
                    if (!dataCardArray[i].find('img').attr('src')) {
                        dataCardArray[i].find('img').attr('src', src);
                        dataCardArray[i].find('[data-name]').text(capitalizedName);
                        dataCardArray[i].removeClass('background-card');
                        if (TYPE_COLOR[types]) {
                            dataCardArray[i].attr('style', TYPE_COLOR[types]);
                        }
                        checkBackgroundCard();


                        dataModalArray[i].attr('id', 'exampleModal' + i);
                        dataModalArray[i].attr('data-name', capitalizedName.toLowerCase());
                        dataModalArray[i].attr('data-modal', i);


                        $.ajax({
                            url: './api/modalPokemon.php',
                            type: 'POST',
                            data: `name=${name}`,
                            success: function(data) {
                                if (data.response == 200) {
                                    // Mise à jour des éléments spécifiques de la modal actuelle utilisant l'index
                                    $('.photo' + i).attr('src', data.pokemonPhoto);
                                    $('.name' + i).text(data.pokemonName.charAt(0).toUpperCase() + data.pokemonName.slice(1));
                                    $('.type' + i).text('Type : ' + data.pokemonType);
                                    $('.height' + i).text('Height : ' + data.pokemonHeight);
                                    $('.weight' + i).text('Weight : ' + data.pokemonWeight);
                                    $('.ability' + i).text('Ability : ' + data.pokemonAbility);

                                } else {
                                    alert(data.message);
                                }
                            }
                        });

                        break; // Sort de la boucle après avoir trouvé le premier élément vide
                    }
                }



                dataCardArray.forEach((element) => {
                    if ($(this).data('name') == element.find('[data-name]').text().toLowerCase()) {
                        $(this).parent().addClass('d-none');
                    }
                })
                checkAndToggleDisable();
            });

            dataCardArray.forEach((element) => {
                element.on('click', function() {
                    element.find('img').attr('src', "");
                    let nameElement = element.find('[data-name]').text().toLowerCase();

                    element.find('[data-name]').text("???");
                    element.removeAttr('style');
                    element.addClass('background-card');
                    checkBackgroundCard();


                    // Chercher l'image du Pokémon caché et enlever la classe d-none
                    $('.pokemon-card.d-none').each(function() {
                        if ($(this).find('.pokemon-img').data('name') == nameElement) {
                            $(this).removeClass('d-none');
                        }
                    });

                    checkAndToggleDisable();
                });
            });

            function checkAndToggleDisable() {
                let allElementsHaveNoBackgroundCard = dataCardArray.every(element => !element.hasClass('background-card'));

                if (allElementsHaveNoBackgroundCard) {
                    $('.pokemon-img').addClass('disabled');
                } else {
                    $('.pokemon-img').removeClass('disabled');
                }
            }

            function checkBackgroundCard() {
                dataCardArray.forEach(function(element, index) {
                    if (element.hasClass('background-card')) {
                        $('[data-bs-target="#exampleModal' + index + '"]').prop('disabled', true);
                    } else {
                        $('[data-bs-target="#exampleModal' + index + '"]').prop('disabled', false);
                    }
                });
            }

            $('#save-team').on('click', function(e) {
                e.preventDefault();
                dataNameArray = dataCardArray.map(element => element.children().next().text().toLowerCase());
                for (let i = 0; i < dataNameArray.length; i++) {
                    if (dataNameArray[i] == null) {
                        alert('Vous devez ajouter au moins un Pokémon');
                        return;
                    }
                }

                let jsonDataNameArray = JSON.stringify(dataNameArray);
                $.ajax({
                    url: './api/saveTeam.php',
                    type: 'POST',
                    data: `name=${jsonDataNameArray}`,
                    success: function(data) {
                        if (data.response == 200) {
                            alert(data.message);
                        } else {
                            alert(data.message);
                        }
                    }
                });
            })

        })
    </script>

</body>

</html>