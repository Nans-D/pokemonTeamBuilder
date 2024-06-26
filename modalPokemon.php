<?php for ($i = 0; $i <= 5; $i++) : ?>
    <div data-modal=<?= $i ?> class="modal fade" id="exampleModal<?= $i ?>" data-name tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 name<?= $i ?>" id="exampleModalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row ">
                        <div class="col-md-6">

                            <img src="" alt="Pokemon" class="img-fluid photo<?= $i ?>">
                        </div>
                        <div class="col-md-6">
                            <p class="modal-title type<?= $i ?>">Type : </p>
                            <p class="modal-title height<?= $i ?>">Height : </p>
                            <p class="modal-title weight<?= $i ?>">Weight : </p>
                            <p class="modal-title ability<?= $i ?>">Ability : </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
<?php endfor; ?>

<script>
    $(document).ready(function() {

    });
</script>