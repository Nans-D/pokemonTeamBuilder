<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Gallery</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/6a8041b5d6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./assets/style.css">

    <style>
        .disabled {
            pointer-events: none;
            opacity: 0.2;
        }

        .pokemon-card {
            margin: 10px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .pokemon-img {
            width: 100px;
            aspect-ratio: 1/1;
        }

        .card-size {
            aspect-ratio: 2.80/4;
            height: 250px;
        }

        .background-card {
            background: linear-gradient(331deg, rgba(13, 21, 32, 1) 0%, rgba(0, 51, 98, 1) 100%);
        }



        @media(max-width:374.98px) {
            .card-size {
                height: 180px;
            }
        }

        @media(min-width:375px) and (max-width:575.98px) {
            .card-size {
                height: 220px;
            }
        }

        @media(min-width:576px) and (max-width:767.98px) {
            .card-size {
                height: 240px;
            }
        }

        @media(min-width:768px) and (max-width:991.98px) {
            .card-size {
                height: 170px;
            }
        }

        @media(min-width:992px) and (max-width:1139.98px) {
            .card-size {
                height: 220px;
            }
        }
    </style>
</head>

<header class="py-3">
    <div class="container-lg row justify-content-around mx-auto ">
        <div class="col align-self-center">
            <a href="./index.php"><img src="./assets/img/logo-team-builder.png" alt="" style="width:120px;"></a>
        </div>

        <div id="nav-bar-button" class="col text-end align-self-center position-relative">
            <div style="cursor:pointer;"><img src="./assets/img/pokeball.png" style="width:30px;" alt=""></div>
            <div id="nav-bar" class="position-absolute d-none end-0 bg-light rounded-4" style="width:150px;top:50px; background: radial-gradient(circle, rgba(112,184,255,1) 0%, rgba(16,77,135,1) 100%);">
                <ul class="d-flex flex-column justify-content-center align-items-center list-unstyled h-100 m-0">
                    <li class="text-center">
                        <a class="link-header text-light text-decoration-none fs-3" href="#">My team</a>
                    </li>
                    <li class="text-center">
                        <a class="link-header text-light text-decoration-none fs-3" method="post" href="<?= ($_SESSION['name'] != null) ? './api/logout.php' : './login.php'; ?>"><?= ($_SESSION['name'] != null) ? 'Logout' : 'Login'; ?></a>
                    </li>

                </ul>

            </div>
        </div>
    </div>
</header>

<script>
    $(document).ready(function() {
        $('.link-header').on('mouseenter', function() {
            $(this).addClass('border-bottom')
        })
        $('.link-header').on('mouseleave', function() {
            $(this).removeClass('border-bottom');
        })

        $('#nav-bar-button').on('click', function() {
            $('#nav-bar').toggleClass('d-block d-none');

        })
    })
</script>