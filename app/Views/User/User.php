<?= $this->extend('Template/Content') ?>

<!-- start content -->
<?= $this->section('sectioncontent') ?>
<div class="container">
    <h1 class="border-bottom border-3 pr-3 pb-3 border-info d-inline-block"><?= @$title ?></h1>

    <!-- alert -->
    <?= $this->include('Template/Alert') ?>

    <div class="row justify-content-center">
        <div class="col-6">
            <form action="<?= base_url('user/update') ?>" method="POST">
                <div class="row">
                    <div class="col-12">
                        <label>Name <small class="text-danger">*</small></label>
                        <input type="hidden" name="id" value="<?= @$user->id ?>">
                        <input type="text" name="name" class="form-control mb-3 <?= $validation->hasError('name') ? 'is-invalid' : '' ?>" value="<?= !empty(old('name')) ? old('name') : @$user->name ?>" required>
                        <small class="text-danger">
                            <?= $validation->getError('name') ?>
                        </small>
                    </div>
                    <div class="col-12">
                        <label>Email <small class="text-danger">*</small></label>
                        <input type="email" name="email" class="form-control mb-3 <?= $validation->hasError('email') ? 'is-invalid' : '' ?>" value="<?= !empty(old('email')) ? old('email') : @$user->email ?>" required>
                        <small class="text-danger">
                            <?= $validation->getError('email') ?>
                        </small>
                    </div>
                    <div class="col-12">
                        <label>Password <small class="text-danger">*</small></label>
                        <input type="password" name="password" class="form-control mb-3 ">
                    </div>
                    <div class="col-12">
                        <label>Address <small class="text-danger">*</small></label>
                        <input type="text" name="address" class="form-control mb-3 <?= $validation->hasError('address') ? 'is-invalid' : '' ?>" value="<?= !empty(old('address')) ? old('address') : @$user->address ?>" required>
                        <small class="text-danger">
                            <?= $validation->getError('address') ?>
                        </small>
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

<!-- js -->
<?= $this->section('sectionjs') ?>

<!-- alert js -->
<?= $this->include('Template/Alertjs') ?>

<?= $this->endSection() ?>

<!-- end content -->