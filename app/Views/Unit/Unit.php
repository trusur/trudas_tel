<?= $this->extend('Template/Content') ?>

<!-- start content -->
<?= $this->section('sectioncontent') ?>
<div class="container">
    <div class="d-flex bd-highlight mb-3">
        <div class="me-auto bd-highlight">
            <h1 class="border-bottom border-3 pr-3 pb-3 border-info d-inline-block"><?= @$title ?></h1>
        </div>
        <div class="bd-highlight">
            <a class="btn btn-sm btn-primary" href="<?= base_url('unit/add') ?>"><i class="fas fa-plus me-2"></i> Add Unit</a>
        </div>
    </div>

    <!-- alert -->
    <?= $this->include('Template/Alert') ?>

    <div class="row">
        <div class="col-12">
            <table class="table table-hovered table-boreded text-center">
                <thead>
                    <th width="50">No</th>
                    <th>Unit Name</th>
                    <th width="150">Action</th>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($units as $unit) : ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $unit->name ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="<?= base_url('unit/edit/' . $unit->id) ?>"><i class="fas fa-edit"></i></a>
                                <a id="getdeleteid" data-deleteid="<?= $unit->id ?>" class="btn btn-danger btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#modal-delete"><i class="fas fa-trash"></i></a>
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
            <form action="<?= base_url('unit/delete') ?>" method="POST">
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