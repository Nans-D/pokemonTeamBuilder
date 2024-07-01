<?php

if (empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] != "XMLHttpRequest") {
    if (realpath($_SERVER["SCRIPT_FILENAME"]) == __FILE__) { // direct access denied
        header("Location: /403");
        exit;
    }
}

require_once './class/user.php';
require_once './class/pokemonTeam.php';

session_start();

header('Content-type: application/json');
$response = [];
try {

    if (!isset($_SESSION['name'])) {
        throw new Exception('You must be logged in to save your team');
    }

    $idUser = $_SESSION['idUser'];
    $pokemonTeam = new PokemonTeam(null, $idUser);
    $team = $pokemonTeam->getTeam($_POST['numeroTeam']);

    $response['response'] = 200;
    $response['team'] = $team;
    echo json_encode($response);
} catch (Exception $e) {
    $response['response'] = 400;
    $response['message'] = $e->getMessage();
    echo json_encode($response);
    exit;
}
