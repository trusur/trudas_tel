<?= $this->extend('Template/Content') ?>

<!-- start content -->
<?= $this->section('sectioncontent') ?>
<div class="container">
    <div class="row">
        <div class="d-flex justify-content-between">
            <h1 class="border-bottom border-3 pr-3 pb-3 border-info d-inline-block"><?= @$title ?></h1>
            <?php if (session('session_id')) : ?>
                <button type="button" id="getrcastatus" data-is_rca="<?= $mode_rca ?>" class="btn <?= $mode_rca == 0 ? 'btn-warning' : 'btn-danger' ?> btn-sm" data-bs-toggle="modal" data-bs-target="#modal-start-mode-rca">
                    <h1 class="mt-2"><?= $mode_rca == 0 ? 'START MODE RCA' : 'STOP MODE RCA' ?></h1>
                </button>
            <?php endif ?>
        </div>
    </div>

    <!-- alert -->
    <?= $this->include('Template/Alert') ?>

    <div class="row">
        <?php if ($mode_rca == 0) : ?>
            <?php foreach ($sensorvalues as $value) : ?>
				<?php if($value->instrument_param_id < 9) : ?>
					<div class="col-lg-3 my-2">
						<div id="changeBG<?= $value->svinstrument_param_id ?>" class="card">
							<div class="card-body">
								<div class="d-flex justify-content-between align-items-center">
									<h1 class="h4"><?= $value->scode ?></h1>
									<p><span class="h1" data-id="<?= $value->svinstrument_param_id ?>"><?= $value->data ?></span> <small class="text-dark"><?= $value->uname ?></small></p>
								</div>
								<div class="d-flex justify-content-end align-items-center">
									<p><span class="h5" data-id="<?= 'v_' . $value->svinstrument_param_id ?>"><?= $value->voltage ?></span> <small class="text-dark">mA</small></p>
								</div>
							</div>
						</div>
					</div>
				<?php else : ?>
					<div class="col-sm my-2">
						<div id="changeBG<?= $value->svinstrument_param_id ?>" class="card">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<h1 class="h6"><?= $value->scode ?></h1>
								</div>
								<div class="d-flex align-items-center justify-content-between">
									<p>&nbsp;</p>
									<p><span class="h4" data-id="<?= $value->svinstrument_param_id ?>"><?= $value->data ?></span> <small class="text-dark"><?= $value->uname ?></small></p>
								</div>
							</div>
						</div>
					</div>
				<?php endif ?>
            <?php endforeach ?>
        <?php else : ?>
            <?php foreach ($sensorvalue_rca as $value_rca) : ?>
                <div class="col-lg-3 my-2">
                    <div id="changeBG<?= $value_rca->svinstrument_param_id ?>" class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h1 class="h4"><?= $value_rca->scode ?></h1>
                                <p><span class="h1" data-id="<?= $value_rca->svinstrument_param_id ?>"><?= $value_rca->data ?></span> <small class="text-dark"><?= $value_rca->uname ?></small></p>
                            </div>
                            <div class="d-flex justify-content-end align-items-center">
                                <p><span class="h5" data-id="<?= 'v_' . $value_rca->svinstrument_param_id ?>"><?= $value_rca->voltage ?></span> <small class="text-dark">mA</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</div>

<!-- modal start mode rca -->
<div class="modal fade" id="modal-start-mode-rca" tabindex="-1" aria-labelledby="modal-start-mode-rca" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-warning">
            <form id="rca_form" action="<?= base_url('start-mode-rca') ?>" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title text-dark">Confirmation!</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="is_rca" id="is_rca">
                    <p class="text-dark">Are You Sure Want to <?= $mode_rca == 0 ? 'Start' : 'Stop' ?> RCA Mode ?&hellip;</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="disable"><?= $mode_rca == 0 ? 'Start' : 'Stop' ?></button>
                </div>
        </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<!-- js -->
<?= $this->section('sectionjs') ?>
<script>
    $(document).ready(function() {
        setInterval(() => {
            $.ajax({
                url: '<?= $mode_rca == 0 ? base_url('getsensorvalue') : base_url('getsensorvalue-rca') ?>',
                type: 'get',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(data) {
                    if (data?.success) {
                        const sensorValue = data?.data;
                        sensorValue.map(function(value, index) {
                            let eldata = $(document).find(`span[data-id=${value?.instrument_param_id}]`);
                            let elvoltage = $(document).find(`span[data-id=v_${value?.instrument_param_id}]`);
                            eldata.html(value?.data);
                            elvoltage.html(value?.voltage);
                            let element = document.getElementById(`changeBG${value?.instrument_param_id}`);
                            if (value?.data <= 0) {
                                element.classList.remove("bg-info");
                                element.classList.add("bg-danger");
                            } else {
                                element.classList.remove("bg-danger");
                                element.classList.add("bg-info");
                            }
                        })
                        return;
                    }
                    console.error(data?.message);
                },
                error: function(xhr, status, err) {
                    console.error(err);
                }
            })
        }, 1000);
    });
</script>

<script>
    $(document).on('click', '#getrcastatus', function() {
        $('#is_rca').val($(this).data('is_rca'));
    })
    $(document).on('click', '#disable', function() {
        $('button').prop('disabled', true)
        $('#rca_form').trigger('submit')
    })
</script>

<!-- alert js -->
<?= $this->include('Template/Alertjs') ?>

<?= $this->endSection() ?>
<!-- end content -->