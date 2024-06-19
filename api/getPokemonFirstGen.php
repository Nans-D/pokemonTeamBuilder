<?php

use PokePHP\PokeApi;
use function PokePHP\getFirstGeneration;

include '../vendor/danrovito/pokephp/src/PokeApi.php';


if (empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] != "XMLHttpRequest") {
    if (realpath($_SERVER["SCRIPT_FILENAME"]) == __FILE__) { // direct access denied
        header("Location: /403");
        exit;
    }
}

header('Content-type: application/json');

$response = [];

try {

    if (empty($_POST['page'])) {
        throw new Exception("page indisponible");
    }

    $pokemonApi = new PokeApi;
    $pokemonFirstGeneration = getFirstGeneration("pokemon", "151");
    $totalPokemon = count($pokemonFirstGeneration);
    $perPage = 8;
    $totalPages = ceil($totalPokemon / $perPage);
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    // var_dump($page);
    // exit;
    $page = max(1, min($page, $totalPages));
    $start = ($page - 1) * $perPage;

    $pokemonPage = array_slice($pokemonFirstGeneration, $start, $perPage);

    $pokemonPhotos = [];
    $pokemonColor = [];

    foreach ($pokemonPage as $pokemon) {
        $pokemonResponse = $pokemonApi->pokemon($pokemon->name);
        $pokemonPhotos[$pokemon->name] = json_decode($pokemonResponse)->sprites->other->{'official-artwork'}->front_default;
        $pokemonColorEncode = $pokemonApi->pokemonSpecies($pokemon->name);
        $pokemonColorString = json_decode($pokemonColorEncode);
        $pokemonColor[$pokemon->name] = $pokemonColorString->color->name;
    }


    $response['response'] = 200;
    $response['html'] = '';
    foreach ($pokemonPage as $pokemon) {
        $response['html'] .= '
            <div class="col-auto">
                <div class="position-relative rounded-5 card-pokemon" style="width:200px; height:250px;background: rgb(13,21,32);background: linear-gradient(331deg, rgba(13,21,32,1) 0%, rgba(0,51,98,1) 100%);">
                    <div class="text-light text-center pt-2">' . ucfirst($pokemon->name) . '</div>
                    <div class="position-absolute top-50 start-50 translate-middle" style="height: 170px;width: 170px;background: rgb(13,21,32);background: radial-gradient(circle, rgba(13,21,32,1) 0%, rgba(16,77,135,1) 0%, rgba(13,21,32,1) 100%);border-radius: 50%;display: inline-block;border:2px solid ' . $pokemonColor[$pokemon->name] . ';">
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <img class="object-fit-contain" style="width:100%;" src="' . $pokemonPhotos[$pokemon->name] . '" alt="">
                        </div>
                    </div>
                    <a id="modal" type="button" class="stretched-link" data-bs-toggle="modal" data-bs-target="#exampleModal"></a>

                </div>
            </div>';
    }
    echo json_encode($response);
    exit;
} catch (Exception $e) {
    $response['response'] = 400;
    $response['message'] = $e->getMessage();
    echo json_encode($response);
    exit;
}
