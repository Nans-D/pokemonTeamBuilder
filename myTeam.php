<?php
session_start();

use PokePHP\PokeApi;

require_once './api/class/user.php';
require_once './api/class/pokemonteam.php';
require 'vendor/autoload.php';




if ($_SESSION['name'] == null) {
    header('Location: ./login.php');
    exit();
}

$pokemonTeam = new PokemonTeam(null, $_SESSION['idUser']);
$pokemonApi = new PokeApi;
// $responses = $pokemonTeam->getTeam();



// $team = [];
// foreach ($responses as $key => $response) {

//     if (!empty($response['first_pokemon'])) {
//         $team[] = json_decode($pokemonApi->Pokemon($response['first_pokemon']));
//     }
//     if (!empty($response['second_pokemon'])) {
//         $team[] = json_decode($pokemonApi->Pokemon($response['second_pokemon']));
//     }
//     if (!empty($response['third_pokemon'])) {
//         $team[] = json_decode($pokemonApi->Pokemon($response['third_pokemon']));
//     }
//     if (!empty($response['fourth_pokemon'])) {
//         $team[] = json_decode($pokemonApi->Pokemon($response['fourth_pokemon']));
//     }
//     if (!empty($response['fifth_pokemon'])) {
//         $team[] = json_decode($pokemonApi->Pokemon($response['fifth_pokemon']));
//     }
//     if (!empty($response['sixth_pokemon'])) {
//         $team[] = json_decode($pokemonApi->Pokemon($response['sixth_pokemon']));
//     }
// }

// var_dump($responses);





// Display the teams
// foreach ($teams as $key => $team) {

//     echo "<pre>";
//     var_dump(count($team));
//     echo "</pre>";
//     // foreach ($team as $key => $pokemon) {
//     //     // echo "<pre>";
//     //     // var_dump($pokemon);
//     //     // echo "</pre>";
//     // }
// }

// exit;




?>

<?php include './header.php'; ?>
<div class="container-xl px-4 text-center">

    <div class="row justify-content-center gx-4">
        <?php for ($i = 1; $i <= 4; $i++) : ?>
            <div class="col-auto">
                <button id="team<?= $i ?>" class="btn btn-primary">Team <?= $i ?></button>
            </div>
        <?php endfor; ?>
    </div>
</div>

<!-- 
<div class='container-xl'>
    <div class='row'></div>
    <?php foreach ($teams as $key => $team) : ?>
        <div class="col-12 mb-4 border border-danger">
            <div>Team <?= $key + 1 ?></div>
            <div class="d-flex flex-column">
                <?php for ($i = 0; $i < count($team); $i++) : ?>
                    <div class="row align-items-center">
                        <div class="col-2">
                            <img src="<?= $team[$i]->sprites->front_default ?>" alt="" class="" width="100" height="100">
                        </div>
                        <div class="col-6"><?= $team[$i]->name ?></div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div> -->

<script>
    $(document).ready(function() {
        let teams = [];
        for (let i = 0; i <= 4; i++) {
            teams.push($(`#team${i}`));
        }

        teams.forEach((element) => {
            element.on('click', function() {
                let teamId = element.attr('id').replace('team', '');
                console.log(teamId);

                $.ajax({
                    url: './api/getTeam.php',
                    type: 'POST',
                    data: `numeroTeam=${teamId}`,
                    success: function(data) {
                        if (data.response == 200) {
                            console.log(data.team);
                        } else {
                            alert(data.message);
                        }
                    }
                })

            })
        });

    });
</script>