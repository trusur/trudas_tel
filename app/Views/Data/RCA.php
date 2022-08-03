<?= $this->extend('Template/Content') ?>

<!-- start content -->
<?= $this->section('sectioncontent') ?>
<div class="container" style="margin-bottom: 100px;">
    <div class="d-flex bd-highlight mb-3 justify-content-between">
        <h1 class="border-bottom border-3 pr-3 pb-3 border-info d-inline-block"><?= @$title ?></h1>
        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modal-reset-data-rca">
            <h5 class="mt-2">RESET DATA RCA</h5>
        </button>
    </div>

    <!-- alert -->
    <?= $this->include('Template/Alert') ?>

    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="row">
                <div class="col-md-10">
                    <select id="instrument_param_id" class="form-control">
                        <option value="">-- Select Parameter --</option>
                        <?php foreach ($sensor as $sList) : ?>
                            <option value="<?= $sList->instrument_param_id ?>"><?= $sList->sensor_code ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" onclick="dataTable.draw()" class="btn btn-primary"><i class="fas fa-search mr-2"></i>Cari</button>
                    <button type="reset" onclick="location.reload()" class="btn btn-secondary"><i class="fas fa-sync mr-2"></i>Reset</button>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table id="rca-log" class="table border-primary table-striped table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th width="1">No</th>
                            <th>Parameter</th>
                            <th>Time</th>
                            <th>Value</th>
                            <th>Correction</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- modal start mode rca -->
<div class="modal fade" id="modal-reset-data-rca" tabindex="-1" aria-labelledby="modal-reset-data-rca" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-warning">
            <form id="rca_form" action="<?= base_url('rca-log/reset-data') ?>" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title text-dark">Confirmation!</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-dark">Are You Sure Want to Reset RCA Data ?&hellip;</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="disable">Reset</button>
                </div>
        </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('sectionheader') ?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-buttons/css/buttons.dataTables.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('sectionjs') ?>

<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/js/dataTables.bootstrap5.min.js') ?>"></script>

<script src="<?= base_url('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-buttons/js/jszip.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-buttons/js/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-buttons/js/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
<script>
    $(document).ready(function() {
        dataTable = $('#rca-log').DataTable({
            "ordering": false,
            "processing": true,
            "serverSide": true,
            "searching": false,
            "pageLength": 10,
            "serverMethod": "post",
            lengthMenu: [
                [10, 50, 100, 300, 500, -1],
                [10, 50, 100, 300, 500, "All"]
            ],
            "ajax": {
                "url": "<?= base_url('/ajax/rca-log') ?>",
                "data": function(data) {
                    data.instrument_param_id = $('#instrument_param_id').val();
                }
            },
            dom: '<"dt-buttons"B><"clear">lfrtip',
            buttons: [{
                text: 'Export To Excel',
                extend: 'excel',
                className: 'btn btn-sm btn-success mb-2',
            }],
        })
        $('.dt-buttons > button').removeClass('dt-button buttons-excel button-html5')
    })
</script>

<!-- alert js -->
<?= $this->include('Template/Alertjs') ?>

<?= $this->endSection() ?>
<!-- end content -->