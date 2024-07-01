<?php
require_once './class/user.php';
require_once './class/pokemonTeam.php';
session_start();


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


    $team = json_decode($_POST['name']);

    if (checkTooMuchTeam($_SESSION['idUser'])) {
        throw new Exception('You can only have 4 team');
    }

    $error = 0;
    foreach ($team as $key => $name) {
        if ($name == '???') {
            $team[$key] = null;
            $error++;
        }
    }

    if ($error == 6) {
        throw new Exception('Please, choose at least one pokemon');
    }

    if (!isset($_SESSION['name'])) {
        throw new Exception('You must be logged in to save your team');
    }



    $idUser = $_SESSION['idUser'];

    $nombreTeam = ckeckNumberTeam($_SESSION['idUser']);


    $pokemonTeam = new PokemonTeam(null, $idUser, $nombreTeam, $team[0], $team[1], $team[2], $team[3], $team[4], $team[5]);

    if (!$pokemonTeam->create()) {
        $response['message'] = throw new Exception('Error while creating team');
    }


    $response['response'] = 200;
    $response['message'] = 'Team saved';
    echo json_encode($response);
    exit;
} catch (Exception $e) {
    $response['response'] = 400;
    $response['message'] = $e->getMessage();
    echo json_encode($response);
    exit;
}
