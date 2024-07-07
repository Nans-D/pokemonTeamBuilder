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

// TYPES

$types = ['normal', 'fighting', 'flying', 'poison', 'ground', 'rock', 'bug', 'ghost', 'steel', 'fire', 'water', 'grass', 'electric', 'psychic', 'ice', 'dragon', 'dark', 'fairy'];


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


    <div class="border border-danger">
        <div class="row justify-content-center">
            <div class="col-auto">
                <ul class="list-unstyled">
                    <?php foreach ($types as $type) : ?>
                        <li class="d-flex justify-content-center align-items-center">
                            <span class="pe-3"><img src="./assets/img/logo_types/<?= $type ?>.png" alt="" width="25" height="25"></span>
                            <ul class="row justify-content-center list-unstyled">
                                <li class="tally-mark col-auto"></li>
                                <li class="tally-mark col-auto"></li>
                                <li class="tally-mark col-auto"></li>
                                <li class="tally-mark col-auto"></li>
                                <li class="tally-mark col-auto"></li>
                                <li class="tally-mark col-auto"></li>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
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