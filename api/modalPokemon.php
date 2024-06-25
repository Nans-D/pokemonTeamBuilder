<?php

use PokePHP\PokeApi;

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
    if (empty($_POST['name'])) {
        throw new Exception("name indisponible");
    }

    $pokemonApi = new PokeApi;
    $pokemonResponse = $pokemonApi->pokemon($_POST['name']);
    $pokemon = json_decode($pokemonResponse);
    $pokemonPhoto = $pokemon->sprites->other->{'official-artwork'}->front_default;
    $pokemonType = isset($pokemon->types[1]) ? $pokemon->types[0]->type->name .  ', ' . $pokemon->types[1]->type->name : $pokemon->types[0]->type->name;
    $pokemonHeight = $pokemon->height;
    $pokemonWeight = $pokemon->weight;
    $pokemonAbility = $pokemon->abilities[0]->ability->name;

    $response['response'] = 200;
    $response['success'] = true;
    $response['pokemonPhoto'] = $pokemonPhoto;
    $response['pokemonType'] = $pokemonType;
    $response['pokemonName'] = $pokemon->name;
    $response['pokemonHeight'] = $pokemonHeight;
    $response['pokemonWeight'] = $pokemonWeight;
    $response['pokemonAbility'] = $pokemonAbility;

    echo json_encode($response);
    exit;
} catch (Exception $e) {
    $response['response'] = 400;
    $response['message'] = $e->getMessage();
    echo json_encode($response);
    exit;
}
