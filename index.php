<?php include('header.php') ?>

<body class="overflow-xl" style="height:100vh;">

    <div class="container-xl">
        <section class="my-2 p-4">
            <div class="row g-3 justify-content-center">
                <?php for ($i = 0; $i < 6; $i++) : ?>
                    <div class="col-6 col-sm-4 col-md-2 col-xl-2">
                        <div data-card="<?= $i ?>" class="rounded-5 card-size background-card mx-auto">
                            <div class="d-flex justify-content-center align-items-center" style="height:80%;">
                                <img class="object-fit-contain build-card" style="width:80%;" src="" alt="">
                            </div>
                            <div data-name class="text-light text-center pt-2">???</div>
                        </div>
                        <div class="d-flex justify-content-center pt-3">
                            <button data-name data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i ?>" class='btn btn-primary align-self-center'>Voir plus</button>
                        </div>
                    </div>
                <?php endfor ?>
            </div>
            <div class='d-flex justify-content-center'>

                <button class='mt-5 px-3 py-1 btn btn-primary'>Save</button>
            </div>
        </section>
        <section class="my-2 p-4">
            <div class="row justify-content-center">
                <?php foreach ($pokemonList as $pokemon) : ?>
                    <?php if (isset($pokemonPhotos[$pokemon->name])) : ?>
                        <div class="col-3 col-sm-2 col-md-auto pokemon-card background-card" data-bs-toggle="popover" data-bs-content="<?= htmlspecialchars(ucfirst($pokemon->name)); ?>" data-bs-placement="top" data-type="<?= htmlspecialchars(implode(',', $pokemonTypes[$pokemon->name])); ?>">
                            <img data-name="<?= $pokemon->name ?>" src="<?= htmlspecialchars($pokemonPhotos[$pokemon->name]); ?>" alt="<?= htmlspecialchars($pokemon->name); ?>" class="object-fit-contain pokemon-img img-fluid">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <?php require_once('./modalPokemon.php') ?>
    <script type="text/javascript">
        const TYPE_COLOR = <?= json_encode(TYPE_COLOR); ?>;

        $(document).ready(function() {
            let dataCardArray = [];
            for (let i = 0; i <= 5; i++) {
                dataCardArray.push($(`[data-card='${i}']`));
            }
            let dataModalArray = [];
            for (let i = 0; i <= 5; i++) {
                dataModalArray.push($(`[data-modal="${i}"]`));
            }

            checkBackgroundCard();

            $('[data-bs-toggle="popover"]').popover({
                trigger: 'hover'
            });


            $('.pokemon-img').on('click', function() {
                let src = $(this).attr('src');
                let name = $(this).data('name');
                let types = $(this).closest('.pokemon-card').data('type');
                let capitalizedName = name.charAt(0).toUpperCase() + name.slice(1);

                for (let i = 0; i < dataCardArray.length; i++) {
                    if (!dataCardArray[i].find('img').attr('src')) {
                        dataCardArray[i].find('img').attr('src', src);
                        dataCardArray[i].find('[data-name]').text(capitalizedName);
                        dataCardArray[i].removeClass('background-card');
                        if (TYPE_COLOR[types]) {
                            dataCardArray[i].attr('style', TYPE_COLOR[types]);
                        }
                        checkBackgroundCard();


                        dataModalArray[i].attr('id', 'exampleModal' + i);
                        dataModalArray[i].attr('data-name', capitalizedName.toLowerCase());
                        dataModalArray[i].attr('data-modal', i);


                        $.ajax({
                            url: './api/modalPokemon.php',
                            type: 'POST',
                            data: `name=${name}`,
                            success: function(data) {
                                if (data.response == 200) {
                                    // Mise à jour des éléments spécifiques de la modal actuelle utilisant l'index
                                    $('.photo' + i).attr('src', data.pokemonPhoto);
                                    $('.name' + i).text(data.pokemonName.charAt(0).toUpperCase() + data.pokemonName.slice(1));
                                    $('.type' + i).text('Type : ' + data.pokemonType);
                                    $('.height' + i).text('Height : ' + data.pokemonHeight);
                                    $('.weight' + i).text('Weight : ' + data.pokemonWeight);
                                    $('.ability' + i).text('Ability : ' + data.pokemonAbility);

                                } else {
                                    alert(data.message);
                                }
                            }
                        });

                        break; // Sort de la boucle après avoir trouvé le premier élément vide
                    }
                }



                dataCardArray.forEach((element) => {
                    if ($(this).data('name') == element.find('[data-name]').text().toLowerCase()) {
                        $(this).parent().addClass('d-none');
                    }
                })
                checkAndToggleDisable();
            });

            dataCardArray.forEach((element) => {
                element.on('click', function() {
                    element.find('img').attr('src', "");
                    let nameElement = element.find('[data-name]').text().toLowerCase();

                    element.find('[data-name]').text("???");
                    element.removeAttr('style');
                    element.addClass('background-card');
                    checkBackgroundCard();


                    // Chercher l'image du Pokémon caché et enlever la classe d-none
                    $('.pokemon-card.d-none').each(function() {
                        if ($(this).find('.pokemon-img').data('name') == nameElement) {
                            $(this).removeClass('d-none');
                        }
                    });

                    checkAndToggleDisable();
                });
            });

            function checkAndToggleDisable() {
                let allElementsHaveNoBackgroundCard = dataCardArray.every(element => !element.hasClass('background-card'));

                if (allElementsHaveNoBackgroundCard) {
                    $('.pokemon-img').addClass('disabled');
                } else {
                    $('.pokemon-img').removeClass('disabled');
                }
            }

            function checkBackgroundCard() {
                dataCardArray.forEach(function(element, index) {
                    if (element.hasClass('background-card')) {
                        $('[data-bs-target="#exampleModal' + index + '"]').prop('disabled', true);
                    } else {
                        $('[data-bs-target="#exampleModal' + index + '"]').prop('disabled', false);
                    }
                });
            }

        })
    </script>

</body>

</html>