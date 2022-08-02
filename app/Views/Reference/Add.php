<?= $this->extend('Template/Content') ?>

<!-- start content -->
<?= $this->section('sectioncontent') ?>
<div class="container">
    <div class="d-flex bd-highlight mb-3">
        <div class="me-auto bd-highlight">
            <h1 class="border-bottom border-3 pr-3 pb-3 border-info d-inline-block"><?= @$title ?></h1>
        </div>
        <div class="bd-highlight">
            <a class="btn btn-sm btn-secondary" href="<?= base_url('reference') ?>"><i class="fas fa-backspace me-2"></i> Back</a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-6">
            <form action="<?= base_url('reference/save') ?>" method="POST">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label>Sensor Code <small class="text-danger">*</small></label>
                        <select name="instrument_param_id" class="form-select <?= $validation->hasError('instrument_param_id') ? 'is-invalid' : '' ?>">
                            <option value="">--Select Sensor Code -- *</option>
                            <?php foreach ($sensors as $sensor) : ?>
                                <option value="<?= $sensor->instrument_param_id ?>"><?= $sensor->sensor_code ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="text-danger">
                            <?= $validation->getError('instrument_param_id') ?>
                        </small>
                    </div>
                    <div class="col-6 mb-3">
                        <label>Range Start <small class="text-danger">*</small></label>
                        <input type="number" name="range_start" class="form-control <?= $validation->hasError('range_start') ? 'is-invalid' : '' ?>" placeholder="ex : 1" value="<?= old('range_start') ?>">
                        <small class="text-danger">
                            <?= $validation->getError('range_start') ?>
                        </small>
                    </div>
                    <div class="col-6 mb-3">
                        <label>Range End <small class="text-danger">*</small></label>
                        <input type="number" name="range_end" class="form-control <?= $validation->hasError('range_end') ? 'is-invalid' : '' ?>" placeholder="ex : 30" value="<?= old('range_end') ?>">
                        <small class="text-danger">
                            <?= $validation->getError('range_end') ?>
                        </small>
                    </div>
                    <div class="col-12 mb-3">
                        <label>Formula <small class="text-danger">*</small></label>
                        <input type="text" name="formula" class="form-control <?= $validation->hasError('formula') ? 'is-invalid' : '' ?>" placeholder="ex : round((6.25 * (AIN + 0.006122)) - 0.25,3)" value="<?= old('formula') ?>">
                        <small class="text-danger">
                            <?= $validation->getError('formula') ?>
                        </small>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save me-2"></i> Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<!-- end content -->