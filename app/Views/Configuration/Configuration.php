<?= $this->extend('Template/Content') ?>

<!-- start content -->
<?= $this->section('sectioncontent') ?>
<div class="container">
    <h1 class="border-bottom border-3 pr-3 pb-3 border-info d-inline-block"><?= @$title ?></h1>

    <!-- alert -->
    <?= $this->include('Template/Alert') ?>

    <div class="row justify-content-center">
        <div class="col-6">
            <form action="<?= base_url('configuration/update') ?>" method="POST">
                <div class="row">
                    <div class="col-6">
                        <label>Das Name 1 <?= @$config->id ?> <small class="text-danger">*</small></label>
                        <input type="hidden" name="id" value="<?= @$config->id ?>">
                        <input type="text" name="name" placeholder="Das Name *" class="form-control mb-3 <?= $validation->hasError('name') ? 'is-invalid' : '' ?>" value="<?= !empty(old('name')) ? old('name') : @$config->name ?>" required>
                        <small class="text-danger">
                            <?= $validation->getError('name') ?>
                        </small>
                    </div>
                    <div class="col-6">
                        <label>Day Backup [number] <small class="text-danger">*</small></label>
                        <input type="number" name="day_backup" placeholder="Day Backup *" class="form-control mb-3 <?= $validation->hasError('day_backup') ? 'is-invalid' : '' ?>" value="<?= !empty(old('day_backup')) ? old('day_backup') : @$config->day_backup ?>" required>
                        <small class="text-danger">
                            <?= $validation->getError('day_backup') ?>
                        </small>
                    </div>
                    <div class="col-6">
                        <label>Server IP <small class="text-danger">*</small></label>
                        <input type="text" name="server_ip" placeholder="Server IP *" class="form-control mb-3 <?= $validation->hasError('server_ip') ? 'is-invalid' : '' ?>" value="<?= !empty(old('server_ip')) ? old('server_ip') : @$config->server_ip ?>" required>
                        <small class="text-danger">
                            <?= $validation->getError('server_ip') ?>
                        </small>
                    </div>
                    <div class="col-6">
                        <label>Analyzer IP <small class="text-danger">*</small></label>
                        <input type="text" name="analyzer_ip" placeholder="Analyzer IP *" class="form-control mb-3 <?= $validation->hasError('analyzer_ip') ? 'is-invalid' : '' ?>" value="<?= !empty(old('analyzer_ip')) ? old('analyzer_ip') : @$config->analyzer_ip ?>" required>
                        <small class="text-danger">
                            <?= $validation->getError('analyzer_ip') ?>
                        </small>
                    </div>
                    <div class="col-md-6">
                        <label>Analyzer Port <small class="text-danger">*</small></label>
                        <input type="number" name="analyzer_port" placeholder="Analyzer Port *" class="form-control mb-3 <?= $validation->hasError('analyzer_port') ? 'is-invalid' : '' ?>" value="<?= !empty(old('analyzer_port')) ? old('analyzer_port') : @$config->analyzer_port ?>" required>
                        <small class="text-danger">
                            <?= $validation->getError('analyzer_port') ?>
                        </small>
                    </div>
                    <div class="col-md-6">
                        <label>Select Unit <small class="text-danger">*</small></label>
                        <input type="number" name="unit_id" class="form-control mb-3 <?= $validation->hasError('unit_id') ? 'is-invalid' : '' ?>" value="<?= !empty(old('unit_id')) ? old('unit_id') : @$config->unit_id ?>" required>
                        <small class="text-danger">
                            <?= $validation->getError('unit_id') ?>
                        </small>
                    </div>
                    <div class="col-md-6">
                        <label>Start Address <small class="text-danger">*</small></label>
                        <input type="number" name="start_addr" placeholder="Start Address *" class="form-control mb-3 <?= $validation->hasError('start_addr') ? 'is-invalid' : '' ?>" value="<?= !empty(old('start_addr')) ? old('start_addr') : @$config->start_addr ?>" required>
                        <small class="text-danger">
                            <?= $validation->getError('start_addr') ?>
                        </small>
                    </div>
                    <div class="col-md-6">
                        <label>Address Num <small class="text-danger">*</small></label>
                        <input type="number" name="addr_num" placeholder="Address Num *" class="form-control mb-3 <?= $validation->hasError('addr_num') ? 'is-invalid' : '' ?>" value="<?= !empty(old('addr_num')) ? old('addr_num') : @$config->addr_num ?>" required>
                        <small class="text-danger">
                            <?= $validation->getError('addr_num') ?>
                        </small>
                    </div>
                    <div class="col-12">
                        <label>Server Url <small class="text-danger">*</small></label>
                        <input type="hidden" name="id" value="<?= @$config->id ?>">
                        <input type="text" name="server_url" placeholder="Server Url *" class="form-control mb-3 <?= $validation->hasError('server_url') ? 'is-invalid' : '' ?>" value="<?= !empty(old('server_url')) ? old('server_url') : @$config->server_url ?>" required>
                        <small class="text-danger">
                            <?= $validation->getError('server_url') ?>
                        </small>
                    </div>
                    <div class="col-12">
                        <label>Server ApiKey <small class="text-danger">*</small></label>
                        <input type="text" name="server_apikey" placeholder="Server ApiKey *" class="form-control mb-3 <?= $validation->hasError('server_apikey') ? 'is-invalid' : '' ?>" value="<?= !empty(old('server_apikey')) ? old('server_apikey') : @$config->server_apikey ?>" required>
                        <small class="text-danger">
                            <?= $validation->getError('server_apikey') ?>
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