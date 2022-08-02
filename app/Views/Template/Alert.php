<!-- start alert -->
<div class="row">
    <div class="col-12">
        <?php if (session()->getFlashdata('message')) : ?>
            <div class="alert alert-success" role="alert">
                <?= session()->getFlashdata('message') ?>
            </div>
        <?php endif ?>
    </div>
</div>
<!-- end alert -->