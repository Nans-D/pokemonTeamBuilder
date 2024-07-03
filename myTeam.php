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


    <div class='mt-5 background-image text-light rounded-5 shadow-lg' style="height:164px">
        <div id="printTeam" class="row justify-content-center p-2 h-100">
            <div class="d-flex justify-content-center align-items-center">
                <p>Select your team !</p>
            </div>
        </div>

    </div>

</div>

<script>
    $(document).ready(function() {
        let teams = [];
        for (let i = 0; i <= 4; i++) {
            teams.push($(`#team${i}`));
        }



        teams.forEach((element) => {
            element.on('click', function() {
                let teamId = element.attr('id').replace('team', '');
                $.ajax({
                    url: './api/getTeam.php',
                    type: 'POST',
                    data: `numeroTeam=${teamId}`,
                    success: function(data) {
                        if (data.response == 200) {
                            $('#printTeam').html(data.team);

                        } else {
                            alert(data.message);
                        }
                    }
                })

            })
        });

    });
</script>