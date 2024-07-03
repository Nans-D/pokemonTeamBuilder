<?php
require_once './class/user.php';
require_once './class/pokemonTeam.php';
require_once '../vendor/autoload.php';

use PokePHP\PokeApi;


session_start();

if (empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] != "XMLHttpRequest") {
    if (realpath($_SERVER["SCRIPT_FILENAME"]) == __FILE__) { // direct access denied
        header("Location: /403");
        exit;
    }
}


header('Content-type: application/json');
$response = [];

$pokemonTeam = new PokemonTeam(null, $_SESSION['idUser']);
$pokemonApi = new PokeApi;

try {

    if (!isset($_SESSION['name'])) {
        throw new Exception('You must be logged in to save your team');
    }

    $idUser = $_SESSION['idUser'];
    $pokemonTeam = new PokemonTeam(null, $idUser);
    $team = $pokemonTeam->getTeam($_POST['numeroTeam']);


    $exportTeam = [];
    $exportTeam[] = is_null($team['first_pokemon']) ? null : json_decode($pokemonApi->Pokemon($team['first_pokemon']));
    $exportTeam[] = is_null($team['second_pokemon']) ? null : json_decode($pokemonApi->Pokemon($team['second_pokemon']));
    $exportTeam[] = is_null($team['third_pokemon']) ? null : json_decode($pokemonApi->Pokemon($team['third_pokemon']));
    $exportTeam[] = is_null($team['fourth_pokemon']) ? null : json_decode($pokemonApi->Pokemon($team['fourth_pokemon']));
    $exportTeam[] = is_null($team['fifth_pokemon']) ? null : json_decode($pokemonApi->Pokemon($team['fifth_pokemon']));
    $exportTeam[] = is_null($team['sixth_pokemon']) ? null : json_decode($pokemonApi->Pokemon($team['sixth_pokemon']));

    // var_dump($exportTeam[0]);
    // exit;
    $response['response'] = 200;
    $response['team'] = '';
    $sprites = [];
    for ($i = 0; $i < count($exportTeam); $i++) {

        if (!is_null($exportTeam[$i])) {

            $response['team'] .= '
            <div class="col-auto mb-4">

                    <div class="d-flex flex-column justify-content-center">
                        <div class="">
                            <img src="' . $exportTeam[$i]->sprites->front_default . '" alt="" width="100" height="100"> 
                        </div>
                        <div class="text-center text-light">' . ucfirst($exportTeam[$i]->name) . '</div>
                    </div>

            </div>';
        } else {
            continue;
        }
    }

    echo json_encode($response);
    exit;
} catch (Exception $e) {
    $response['response'] = 400;
    $response['message'] = $e->getMessage();
    echo json_encode($response);
    exit;
}
