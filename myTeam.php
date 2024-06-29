<?php
session_start();

require_once './api/class/user.php';
require_once './api/class/pokemonteam.php';



if ($_SESSION['name'] == null) {
    header('Location: ./login.php');
    exit();
}

$pokemonTeam = new PokemonTeam(null, $_SESSION['idUser']);
$responses = $pokemonTeam->getTeam();


?>

<?php include './header.php'; ?>

<div class='container-xl'>
    <div class='row'></div>
    <?php foreach ($responses as $key => $value) : ?>

        <div class="col-12 mb-4 border border-danger">
            <div>Team <?= $key + 1 ?></div>
            <div class="row">
                <div class="col-6">
                    <div><?= $responses[$key]['first_pokemon'] ?></div>
                </div>
                <div class="col-6">
                    <div><?= $responses[$key]['second_pokemon'] ?></div>
                </div>
                <div class="col-6">
                    <div><?= $responses[$key]['third_pokemon'] ?></div>
                </div>
                <div class="col-6">
                    <div><?= $responses[$key]['fourth_pokemon'] ?></div>
                </div>
                <div class="col-6">
                    <div><?= $responses[$key]['fifth_pokemon'] ?></div>
                </div>
                <div class="col-6">
                    <div><?= $responses[$key]['sixth_pokemon'] ?></div>
                </div>

            </div>
        </div>

    <?php endforeach; ?>
</div>
</div>