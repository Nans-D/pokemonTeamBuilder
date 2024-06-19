<?php

use PokePHP\PokeApi;

use function PokePHP\getFirstGeneration;

include './vendor/danrovito/pokephp/src/PokeApi.php';

$pokemonApi = new PokeApi;
$pokemonFirstGeneration = getFirstGeneration("pokemon", "151");

// Pagination
$totalPokemon = count($pokemonFirstGeneration);
$perPage = 8;
$totalPages = ceil($totalPokemon / $perPage);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, min($page, $totalPages));

$start = ($page - 1) * $perPage;

$pokemonPage = array_slice($pokemonFirstGeneration, $start, $perPage);


// tableaux
$pokemonPhotos = [];
$pokemonColor = [];

foreach ($pokemonPage as $pokemon) {
    $pokemonResponse = $pokemonApi->pokemon($pokemon->name);
    $pokemonPhotos[$pokemon->name] = json_decode($pokemonResponse)->sprites->other->{'official-artwork'}->front_default;
    $pokemonColorEncode = $pokemonApi->pokemonSpecies($pokemon->name);
    $pokemonColorString = json_decode($pokemonColorEncode);
    $pokemonColor[$pokemon->name] = $pokemonColorString->color->name;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6a8041b5d6.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="./assets/style.css">

    <style>
        .pokemon-card {
            width: 280px;
            height: 350px;
            padding: 13px;
            border-radius: 10px;
            background-color: lightgrey;
            box-shadow: 5px 5px 10px black;


        }

        .pokemon-inside-card {
            width: 100%;
            height: 100%;
            background: rgb(186, 186, 186);
            background: linear-gradient(191deg, rgba(186, 186, 186, 1) 0%, rgba(237, 237, 237, 1) 18%, rgba(149, 149, 149, 1) 40%, rgba(245, 245, 245, 1) 60%, rgba(191, 191, 191, 1) 77%, rgba(122, 122, 122, 1) 100%);
            box-shadow: inset 2px 2px 5px black;
            display: flex;
            flex-direction: column;
        }


        .shadow-filter-pokemon {
            box-shadow: 0 0 10px black inset;
        }

        .card-pokemon:hover {
            box-shadow: 5px 5px 10px #70B8FF;
            cursor: pointer;

        }

        @media(min-width:1200px) {
            .overflow-xl {
                overflow: hidden;
            }
        }
    </style>
</head>

<body class="overflow-xl h-100" style="max-height:100vh;">
    <section class="py-3" style="height:40vh;">
        <div class="background-image"></div>
        <div class="container-lg row justify-content-around mx-auto">
            <div class="col align-self-center">
                <img src="./assets/img/International_Pokémon_logo.svg.png" alt="" style="width:150px;">
            </div>
            <div class="col">
                <ul class="row list-unstyled h-100">
                    <li class="col text-center align-self-center">
                        <a class="text-light text-decoration-none" href="#">Home</a>
                    </li>
                    <li class="col text-center align-self-center">
                        <a class="text-light text-decoration-none" href="#">About</a>
                    </li>
                    <li class="col text-center align-self-center">
                        <a class="text-light text-decoration-none" href="#">Contact</a>
                    </li>
                </ul>
            </div>
            <div class="col text-end align-self-center">
                <button class="btn"><img src="./assets/img/pokeball.png" style="width:30px;" alt=""></button>
            </div>
        </div>
    </section>

    <section class="container-fluid">
        <div class="row align-items-center g-0">
            <div class="col-4">
                <div class="fs-1 text-light text-center w-100 text-bold">FILTER</div>
                <ul class="list-unstyled row justify-content-center flex-column mx-auto">
                    <li class="col-auto filter-button py-1 px-3 rounded-5 mx-auto my-3 text-center" style='background-color:#003362;box-shadow:5px 5px 10px #0D1520;'>
                        <a class="text-light text-decoration-none fs-2" href="">Génération</a>
                    </li>
                    <li class=" col-auto filter-button py-1 px-3 rounded-5 mx-auto my-3 text-center" style='background-color:#003362;box-shadow:5px 5px 10px #0D1520;'>
                        <a class="text-light text-decoration-none fs-2" href="">Color</a>
                    </li>
                    <li class=" col-auto filter-button py-1 px-3 rounded-5 mx-auto my-3 text-center" style='background-color:#003362;box-shadow:5px 5px 10px #0D1520;'>
                        <a class="text-light text-decoration-none fs-2" href="">Evolution</a>
                    </li>
                </ul>


                <nav class="mt-5">
                    <ul class="row justify-content-center list-unstyled" style="gap: 10px;">
                        <li class="col-auto li-pagination <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= 4; $i++) : ?>
                            <li class="col-auto li-pagination <?= ($i == $page) ? 'active' : '' ?>">
                                <a data-page="<?= $i ?>" class=" page-switch" type="button" href="?page=<?= $i ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor ?>
                        <input id="input-pagination" type="text" class="col-auto li-pagination border border-none" name="" id="">
                        <li class="col-auto li-pagination <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <button id="button-pagination" data-page disabled class="col-auto btn btn-primary">Go</button>
                    </ul>
                </nav>
            </div>
            <div class="col-8 position-relative">
                <div id="spinner" class="d-none position-absolute top-50 start-50 translate-middle z-1">
                    <img src="./assets/img/pokeball.png" alt="Pokéball" class="spin-pokeball" style="width:120px;">
                </div>
                <div id="printPokemonNewPage" class="row row-cols-4 gy-3 justify-content-center mx-auto align-content-center" style="height:60vh;">
                    <?php foreach ($pokemonPage as $pokemon) { ?>

                        <div class="col-3">
                            <div class="position-relative rounded-5 card-pokemon" style="width:200px; height:250px;background: rgb(13,21,32);background: linear-gradient(331deg, rgba(13,21,32,1) 0%, rgba(0,51,98,1) 100%);">
                                <div class="text-light text-center pt-2"><?= ucfirst($pokemon->name) ?></div>
                                <div class="position-absolute top-50 start-50 translate-middle" style="height: 170px;width: 170px;background: rgb(13,21,32);background: radial-gradient(circle, rgba(13,21,32,1) 0%, rgba(16,77,135,1) 0%, rgba(13,21,32,1) 100%);border-radius: 50%;display: inline-block;border:2px solid <?= $pokemonColor[$pokemon->name]; ?>;">
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <img class="object-fit-contain" style="width:100%;" src="<?= $pokemonPhotos[$pokemon->name] ?>" alt="">
                                    </div>
                                </div>
                                <a id="modal" type="button" class="stretched-link" data-bs-toggle="modal" data-bs-target="#exampleModal"></a>
                            </div>

                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <?php require_once('./modalPokemon.php') ?>
    <script>
        $(document).ready(function() {
            $('.filter-button').on('mouseenter', function() {
                $(this).addClass('shadow-filter-pokemon ')
            })
            $('.filter-button').on('mouseleave', function() {
                $(this).removeClass('shadow-filter-pokemon ')
            })


            $(document).on('click', ".page-switch, #button-pagination", function(e) {
                e.preventDefault();
                e.stopPropagation();

                let page = $(this).data('page');
                console.log($(this), page);

                $.ajax({
                    url: './api/getPokemonFirstGen.php',
                    type: 'POST',
                    data: `page=${page}`,
                    beforeSend: function() {
                        $('#spinner').removeClass('d-none');
                        $('#printPokemonNewPage').addClass('blur-cards');
                    },
                    success: function(data) {
                        if (data.response == 200) {
                            $('#printPokemonNewPage').empty().append(data.html);
                        }
                    },
                    complete: function() {
                        $('#spinner').addClass('d-none');
                        $('#printPokemonNewPage').removeClass('blur-cards');
                    }
                });
            });


            $('#input-pagination').on('input', function() {
                let page = $(this).val();

                if (page) {
                    $('#button-pagination').prop('disabled', false).data('page', page);
                } else {
                    $('#button-pagination').prop('disabled', true);
                }
            });


        })
    </script>
</body>

</html>