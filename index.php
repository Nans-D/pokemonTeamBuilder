<?php

use PokePHP\PokeApi;

use function PokePHP\getFirstGeneration;

include './vendor/danrovito/pokephp/src/PokeApi.php';

$pokemonApi = new PokeApi;
$pokemonFirstGeneration = getFirstGeneration();

$totalPokemon = count($pokemonFirstGeneration);
$perPage = 12;
$totalPages = ceil($totalPokemon / $perPage);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, min($page, $totalPages));
$start = ($page - 1) * $perPage;

$pokemonPage = array_slice($pokemonFirstGeneration, $start, $perPage);

$pokemonPhotos = [];



foreach ($pokemonPage as $pokemon) {
    $pokemonResponse = $pokemonApi->pokemon($pokemon->name);
    $pokemonPhotos[$pokemon->name] = json_decode($pokemonResponse)->sprites->other->{'official-artwork'}->front_default;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6a8041b5d6.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="./assets/style.css">
</head>

<body class="background-image">
    <div class="row container-lg justify-content-center mx-auto">
        <div class="row my-3 p-0 justify-content-center align-items-center">
            <div class="col-2 align-self-center">
                <div class="row">
                    <img class="col-auto" src="./assets/img/International_Pokémon_logo.svg.png" alt="" style="width:150px;">
                </div>
            </div>
            <div class="col-8 mx-auto">
                <div class="d-flex justify-content-center">

                    <div class="input-container input-group" style="width:50%;">
                        <input id="search" type="text" class="form-control" placeholder="Rechercher" aria-label="Username" aria-describedby="basic-addon1">
                        <span class="input-group-text" id="basic-addon1">@</span>
                    </div>

                </div>
            </div>
            <div class="col-2 align-self-center d-flex justify-content-end">
                <button class="btn"><img src="./assets/img/pokeball.png" style="width:30px;" alt=""></button>
            </div>
        </div>


        <div class="d-flex justify-content-center mt-5">
            <div class="row row-cols-md-6 gap-3 justify-content-center mx-auto">
                <?php foreach ($pokemonPage as $pokemon) : ?>
                    <a class="position-relative col rounded-4" style="cursor:pointer;width:190px; height:280px; background-color:#ecb223;">
                        <div class="py-3 w-100 h-100">
                            <div class="w-100 h-100 back" style="background-image:url(./assets/img/550483-lumiere-abstraite-eclate-fond-degrade-radial-jaune-rayon-sunburst-vectoriel.jpg); background-size:cover;">
                                <div class="row px-3 h-100">
                                    <div class="col-12">
                                        <div class="row align-items-center">
                                            <div class="col-8 p-0">
                                                <div class="row">
                                                    <div class="col-12" style="font-size:8px;">Basic Pokemon</div>
                                                    <div class="col-12" style="font-size:10px;"><?= $pokemon->name ?></div>
                                                </div>
                                            </div>
                                            <div class="col-4 p-0 text-end" style="font-size:10px;">40hp</div>
                                        </div>
                                    </div>
                                    <div class="col-12 p-0 w-100 h-50">
                                        <div class="position-relative shadow border border-5 border-danger w-100 h-100" style="background-image:url(./assets/img/fond-card.webp); background-size:cover;">
                                            <div class="p-3 w-100 h-100">

                                                <div class="w-100 h-100" style="background-image:url(<?= $pokemonPhotos[$pokemon->name] ?>); background-size:contain; background-repeat:no-repeat; background-position:center;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row p-1 align-items-center">
                                            <div class="col-3 text-start" style="font-size:8px;"><i class="fa-solid fa-circle"></i></div>
                                            <div class="col-6 text-center" style="font-size:10px;">Attack</div>
                                            <div class="col-3 text-end" style="font-size:12px;">50</div>
                                            <hr>
                                        </div>
                                        <div class="row p-1 align-items-center">
                                            <div class="col-3 text-start" style="font-size:8px;"><i class="fa-solid fa-circle"></i></div>
                                            <div class="col-6 text-center" style="font-size:10px;">Attack</div>
                                            <div class="col-3 text-end" style="font-size:12px;">50</div>
                                            <hr>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach ?>
                <nav aria-label="Page navigation example" class="mt-5">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
                        <?php endfor ?>
                        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>


</body>

</html>