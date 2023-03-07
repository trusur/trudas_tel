<?= $this->extend('Template/Content') ?>

<!-- start content -->
<?= $this->section('sectioncontent') ?>
<div class="container" style="margin-bottom: 100px;">
    <div class="d-flex bd-highlight mb-3">
        <div class="me-auto bd-highlight">
            <h1 class="border-bottom border-3 pr-3 pb-3 border-info d-inline-block"><?= @$title ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="row">
                <div class="col-md-2">
					<label>Data Source</label>
                    <select id="table_source" class="form-control">
                        <option value="das_logs">-- Select Data Source --</option>
                        <?php foreach ($tables as $table) : ?>
                            <option value="<?= $table ?>"><?= $table ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-md-2">
					<label>Parameter</label>
                    <select id="instrument_param_id" class="form-control">
                        <option value="">-- Select Parameter --</option>
                        <?php foreach ($sensor as $sList) : ?>
                            <option value="<?= $sList->instrument_param_id ?>"><?= $sList->sensor_code ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
				<div class="col-md-2">
					<label>Date Time Start</label>
					<input name="date_start" type="datetime-local" class="form-control"/>
				</div>
				<div class="col-md-2">
					<label>Date Time End</label>
					<input name="date_end" type="datetime-local" class="form-control"/>
				</div>
                <div class="col-md-2 py-4">
                    <button type="button" onclick="dataTable.draw()" class="btn btn-primary"><i class="fas fa-search mr-2"></i>Cari</button>
                    <button type="reset" onclick="location.reload()" class="btn btn-secondary"><i class="fas fa-sync mr-2"></i>Reset</button>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table id="das-log" class="table border-primary table-striped table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Datetime</th>
                            <th>Parameter</th>
                            <th>Data</th>
                            <th>Voltage</th>
                            <th>Unit</th>
                            <th>Sent</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
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
        dataTable = $('#das-log').DataTable({
            order : [[0,'desc']],
            // "ordering": false,
            "processing": true,
            "serverSide": true,
            "searching": false,
            "pageLength": 10,
            "serverMethod": "get",
            lengthMenu: [
                [10, 50, 100, 300, 500, -1],
                [10, 50, 100, 300, 500, "All"]
            ],
            "ajax": {
                "url": "<?= base_url('/ajax/das-log') ?>",
                "data": function(data) {
					data.table_source = $('#table_source').val();
                    data.instrument_param_id = $('#instrument_param_id').val();
					data.date_start = $('input[name="date_start"]').val();
					data.date_end = $('input[name="date_end"]').val();
                }
            },
            dom: '<"dt-buttons"B><"clear">lfrtip',
            buttons: [{
                text: 'Export To Excel',
                extend: 'excel',
                className: 'btn btn-sm btn-success mb-2',
            }],
            columns : [
                {data:'id'},
                {data:'parameter'},
                {data:'measured_at'},
                {data:'data'},
                {data:'voltage'},
                {data:'unit'},
                {
                    data:'is_sent',
                    render : function(data, type,row){
                        if(row.is_sent == 1){
                            return `<span class='badge bg-success'>SENT</span>`
                        }
                        return `<span class='badge bg-danger'>NOT SENT</span>`
                    }
                },
            ]
        })
        $('.dt-buttons > button').removeClass('dt-button buttons-excel button-html5')
    })
</script>
<?= $this->endSection() ?>

<!-- end content -->