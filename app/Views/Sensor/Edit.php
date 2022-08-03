<?= $this->extend('Template/Content') ?>

<!-- start content -->
<?= $this->section('sectioncontent') ?>
<div class="container">
    <div class="d-flex bd-highlight mb-3">
        <div class="me-auto bd-highlight">
            <h1 class="border-bottom border-3 pr-3 pb-3 border-info d-inline-block"><?= @$title ?></h1>
        </div>
        <div class="bd-highlight">
            <a class="btn btn-sm btn-secondary" href="<?= base_url('sensor') ?>"><i class="fas fa-backspace me-2"></i> Back</a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-6">
            <form action="<?= base_url('sensor/update') ?>" method="POST">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label>LABJACK IP <small class="text-danger">*</small></label>
                        <input type="hidden" name="id" value="<?= $sensor->id ?>">
                        <input type="text" name="labjack_ip" class="form-control <?= $validation->hasError('labjack_ip') ? 'is-invalid' : '' ?>" placeholder="ex : 192.168.100.2" value="<?= !empty(old('labjack_ip')) ? old('labjack_ip') : $sensor->labjack_ip ?>">
                        <small class="text-danger">
                            <?= $validation->getError('labjack_ip') ?>
                        </small>
                    </div>
                    <div class="col-6 mb-3">
                        <label>AIN <small class="text-danger">*</small></label>
                        <input type="number" name="ain" class="form-control <?= $validation->hasError('ain') ? 'is-invalid' : '' ?>" placeholder="ex : 1" value="<?= !empty(old('ain')) ? old('ain') : $sensor->ain ?>">
                        <small class="text-danger">
                            <?= $validation->getError('ain') ?>
                        </small>
                    </div>
                    <div class="col-6 mb-3">
                        <label>Instrument Parameter ID <small class="text-danger">*</small></label>
                        <input type="number" name="instrument_param_id" class="form-control <?= $validation->hasError('instrument_param_id') ? 'is-invalid' : '' ?>" placeholder="ex : 1" value="<?= !empty(old('instrument_param_id')) ? old('instrument_param_id') : $sensor->instrument_param_id ?>">
                        <small class="text-danger">
                            <?= $validation->getError('instrument_param_id') ?>
                        </small>
                        <?php if (session()->getFlashdata('e_instrument_param_id')) : ?>
                            <small class="text-danger">
                                <?= session()->getFlashdata('e_instrument_param_id') ?>
                            </small>
                        <?php endif ?>
                    </div>
                    <div class="col-6 mb-3">
                        <label>Sensor Code <small class="text-danger">*</small></label>
                        <input type="text" name="sensor_code" class="form-control <?= $validation->hasError('sensor_code') ? 'is-invalid' : '' ?>" placeholder="ex : PM" value="<?= !empty(old('sensor_code')) ? old('sensor_code') : $sensor->sensor_code ?>">
                        <small class="text-danger">
                            <?= $validation->getError('sensor_code') ?>
                        </small>
                        <?php if (session()->getFlashdata('e_sensor_code')) : ?>
                            <small class="text-danger">
                                <?= session()->getFlashdata('e_sensor_code') ?>
                            </small>
                        <?php endif ?>
                    </div>
                    <div class="col-12 mb-3">
                        <label>Unit Name <small class="text-danger">*</small></label>
                        <select name="unit_id" class="form-select <?= $validation->hasError('unit_id') ? 'is-invalid' : '' ?>">
                            <?php foreach ($units as $unit) : ?>
                                <option value="<?= $unit->id ?>" <?= empty(old('unit_id')) ? ($unit->id == $sensor->unit_id ? 'selected' : '') : (old('unit_id') == $unit->id ? 'selected' : '') ?>><?= $unit->name ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="text-danger">
                            <?= $validation->getError('unit_id') ?>
                        </small>
                    </div>
                    <div class="col-12 mb-3">
                        <label>Formula <small class="text-danger">*</small></label>
                        <input type="text" name="formula" class="form-control <?= $validation->hasError('formula') ? 'is-invalid' : '' ?>" placeholder="ex : round((6.25 * (AIN + 0.006122)) - 0.25,3)" value="<?= !empty(old('formula')) ? old('formula') : $sensor->formula ?>">
                        <small class="text-danger">
                            <?= $validation->getError('formula') ?>
                        </small>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Is Multi Parameter *</label>
                        <select name="is_multi_parameter" class="form-control <?= $validation->hasError('is_multi_parameter') ? 'is-invalid' : '' ?>">
                            <option value="0" <?= empty(old('is_multi_parameter')) ? ($sensor->is_multi_parameter == '0' ? 'selected' : '') : (old('is_multi_parameter') == '0' ? 'selected' : '') ?>>No</option>
                            <option value="1" <?= empty(old('is_multi_parameter')) ? ($sensor->is_multi_parameter == '1' ? 'selected' : '') : (old('is_multi_parameter') == '1' ? 'selected' : '') ?>>Yes</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= $validation->getError('is_multi_parameter') ?>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Is Show *</label>
                        <select name="is_show" class="form-control <?= $validation->hasError('is_show') ? 'is-invalid' : '' ?>">
                            <option value="1" <?= empty(old('is_show')) ? ($sensor->is_show == '1' ? 'selected' : '') : (old('is_show') == '1' ? 'selected' : '') ?>>Yes</option>
                            <option value="0" <?= empty(old('is_show')) ? ($sensor->is_show == '0' ? 'selected' : '') : (old('is_show') == '0' ? 'selected' : '') ?>>No</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= $validation->getError('is_show') ?>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">EXTRA Parameter</label>
                        <select id="extra_parameter" name="extra_parameter" class="form-control <?= $validation->hasError('extra_parameter') ? 'is-invalid' : '' ?>">
                            <option value="0" <?= empty(old('extra_parameter')) ? ($sensor->extra_parameter == '0' ? 'selected' : '') : (old('extra_parameter') == '0' ? 'selected' : '') ?>>No</option>
                            <option value="1" <?= empty(old('extra_parameter')) ? ($sensor->extra_parameter == '1' ? 'selected' : '') : (old('extra_parameter') == '1' ? 'selected' : '') ?>>O2</option>
                            <option value="2" <?= empty(old('extra_parameter')) ? ($sensor->extra_parameter == '2' ? 'selected' : '') : (old('extra_parameter') == '2' ? 'selected' : '') ?>>Prameter RCA</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= $validation->getError('extra_parameter') ?>
                        </div>
                    </div>
                    <div id="rca_o2_correction" class="col-6 mb-3 <?= $sensor->extra_parameter == 2 ? '' : 'visually-hidden' ?>">
                        <label class="form-label">RCA O2 Correction</label>
                        <select name="o2_correction" class="form-control">
                            <option value="0" <?= empty(old('o2_correction')) ? ($sensor->o2_correction == '0' ? 'selected' : '') : (old('o2_correction') == '0' ? 'selected' : '') ?>>No</option>
                            <option value="1" <?= empty(old('o2_correction')) ? ($sensor->o2_correction == '1' ? 'selected' : '') : (old('o2_correction') == '1' ? 'selected' : '') ?>>Yes</option>
                        </select>
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

<?= $this->section('sectionjs') ?>
<script>
    $(document).ready(function() {
        $('#extra_parameter').change(function() {
            if ($('#extra_parameter').val() == 2) {
                $('#rca_o2_correction').removeClass('visually-hidden');
            } else {
                $('#rca_o2_correction').addClass('visually-hidden');
            }
        });
    })
</script>

<?= $this->endSection() ?>
<!-- end content -->