<?= $this->extend('Template/Content') ?>

<!-- start content -->
<?= $this->section('sectioncontent') ?>
<div class="container">
    <div class="d-flex bd-highlight mb-3">
        <div class="me-auto bd-highlight">
            <h1 class="border-bottom border-3 pr-3 pb-3 border-info d-inline-block"><?= @$title ?></h1>
        </div>
        <div class="bd-highlight">
            <a class="btn btn-sm btn-primary" href="<?= base_url('sensor/add') ?>"><i class="fas fa-plus me-2"></i> Add Sensor</a>
        </div>
    </div>

    <!-- alert -->
    <?= $this->include('Template/Alert') ?>

    <div class="row">
        <div class="col-12">
            <table class="table table-hovered table-boreded text-center">
                <thead>
                    <th width="50">No</th>
                    <th width="100">Labjack IP</th>
                    <th width="100">AIN</th>
                    <th width="100">Instrument Param ID</th>
                    <th width="250">Code</th>
                    <th width="50">Unit</th>
                    <th>Formula</th>
                    <th>Is Multi</th>
                    <th>Is Show</th>
                    <th>Extra Parameter</th>
                    <th width="150">Action</th>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($sensors as $sensor) : ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $sensor->labjack_ip ?></td>
                            <td><?= $sensor->ain ?></td>
                            <td><?= $sensor->instrument_param_id ?></td>
                            <td><?= $sensor->sensor_code ?></td>
                            <td><?= $sensor->uname ?></td>
                            <td><?= $sensor->formula ?></td>
                            <td><?= $sensor->is_multi_parameter == 0 ? '<span class="btn btn-danger btn-sm">No</span>' : '<span class="btn btn-success btn-sm">Yes</span>' ?></td>
                            <td><?= $sensor->is_show == 0 ? '<span class="btn btn-danger btn-sm">No</span>' : '<span class="btn btn-success btn-sm">Yes</span>' ?></td>
                            <td><?= $sensor->extra_parameter == 0 ? '<span class="btn btn-danger btn-sm">No</span>' : ($sensor->extra_parameter == 1 ? '<span class="btn btn-warning btn-sm">O2</span>' : '<span class="btn btn-warning btn-sm">Parameter RCA</span>') ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="<?= base_url('sensor/edit/' . $sensor->sid) ?>"><i class="fas fa-edit"></i></a>
                                <a id="getdeleteid" data-deleteid="<?= $sensor->sid ?>" class="btn btn-danger btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#modal-delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- modal delete -->
<div class="modal fade" id="modal-delete" tabindex="-1" aria-labelledby="modal-delete" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-danger">
            <form action="<?= base_url('sensor/delete') ?>" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title text-white">Confirmation!</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="deleteid">
                    <p class="text-white">Are You sure want to delete this data ?&hellip;</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Delete</button>
                </div>
        </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('sectionjs') ?>
<script>
    // get id
    $(document).on('click', '#getdeleteid', function() {
        $('#deleteid').val($(this).data('deleteid'));
    })
</script>

<!-- alert js -->
<?= $this->include('Template/Alertjs') ?>

<?= $this->endSection() ?>

<!-- end content -->