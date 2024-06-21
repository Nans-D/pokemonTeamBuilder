<header class="py-3">
    <div class="container-lg row justify-content-around mx-auto">
        <div class="col align-self-center">
            <img src="./assets/img/International_Pokémon_logo.svg.png" alt="" style="width:150px;">
        </div>
        <div class="col">
            <ul class="row list-unstyled h-100">
                <li class="col text-center align-self-center">
                    <a class="link-header text-light text-decoration-none fs-3" href="#">Home</a>
                </li>
                <li class="col text-center align-self-center">
                    <a class="link-header text-light text-decoration-none fs-3" href="#">About</a>
                </li>
                <li class="col text-center align-self-center">
                    <a class="link-header text-light text-decoration-none fs-3" href="#">Contact</a>
                </li>
            </ul>
        </div>
        <div class="col text-end align-self-center">
            <button class="btn"><img src="./assets/img/pokeball.png" style="width:30px;" alt=""></button>
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
    })
</script>