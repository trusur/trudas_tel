<?= $this->extend('Template/Content') ?>

<!-- start content -->
<?= $this->section('sectioncontent') ?>
<div class="container">
    <h1 class="border-bottom border-3 pr-3 pb-3 border-info d-inline-block"><?= @$title ?></h1>
    <div class="row justify-content-center">
        <div class="col-6">
            <form action="<?= base_url('login-session') ?>" method="POST">
                <div class="row">
                    <div class="col-12">
                        <label>Username <small class="text-danger">*</small></label>
                        <input type="email" name="email" class="form-control mb-3 <?= $validation->hasError('email') ? 'is-invalid' : '' ?>" value="<?= old('email') ?>" required>
                        <small class="text-danger">
                            <?= $validation->getError('email') ?>
                        </small>
                        <?php if (session()->getFlashdata('noemail')) : ?>
                            <small class="text-danger">
                                <?= session()->getFlashdata('noemail') ?>
                            </small>
                        <?php endif ?>
                    </div>
                    <div class="col-12">
                        <label>Password <small class="text-danger">*</small></label>
                        <input type="password" name="password" class="form-control mb-3 <?= $validation->hasError('password') ? 'is-invalid' : '' ?>" required>
                        <small class="text-danger">
                            <?= $validation->getError('password') ?>
                        </small>
                        <?php if (session()->getFlashdata('notmatch')) : ?>
                            <small class="text-danger">
                                <?= session()->getFlashdata('notmatch') ?>
                            </small>
                        <?php endif ?>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<!-- end content -->