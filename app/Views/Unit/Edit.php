<?= $this->extend('Template/Content') ?>

<!-- start content -->
<?= $this->section('sectioncontent') ?>
<div class="container">
    <div class="d-flex bd-highlight mb-3">
        <div class="me-auto bd-highlight">
            <h1 class="border-bottom border-3 pr-3 pb-3 border-info d-inline-block"><?= @$title ?></h1>
        </div>
        <div class="bd-highlight">
            <a class="btn btn-sm btn-secondary" href="<?= base_url('unit') ?>"><i class="fas fa-backspace me-2"></i> Back</a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-6">
            <form action="<?= base_url('unit/update') ?>" method="POST">
                <div class="row">
                    <div class="col-12">
                        <label>Unit Name <small class="text-danger">*</small></label>
                        <input type="hidden" name="id" value="<?= $unit->id ?>">
                        <input type="text" name="name" class="form-control <?= $validation->hasError('name') ? 'is-invalid' : '' ?>" placeholder="ex : ppm" value="<?= !empty(old('bane')) ? old('bane') : $unit->name ?>">
                        <small class="text-danger">
                            <?= $validation->getError('name') ?>
                        </small>
                        <?php if (session()->getFlashdata('u_name')) : ?>
                            <small class="text-danger">
                                <?= session()->getFlashdata('u_name') ?>
                            </small>
                        <?php endif ?>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save me-2"></i>Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<!-- end content -->