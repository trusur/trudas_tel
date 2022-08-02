<?= $this->extend('Template/Content') ?>

<!-- start content -->
<?= $this->section('sectioncontent') ?>
<div class="container">
    <h1 class="border-bottom border-3 pr-3 pb-3 border-info d-inline-block"><?= @$title ?></h1>

    <!-- alert -->
    <?= $this->include('Template/Alert') ?>

    <div class="row">
        <?php foreach ($sensorvalues as $value) : ?>
            <div class="col-lg-4 my-2">
                <div id="changeBG<?= $value->svinstrument_param_id ?>" class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="h4"><?= $value->scode ?></h1>
                            <p><span class="h1" data-id="<?= $value->svinstrument_param_id ?>"><?= $value->data ?></span> <small class="text-dark"><?= $value->uname ?></small></p>
                        </div>
                        <div class="d-flex justify-content-end align-items-center">
                            <p><span class="h5" data-id="<?= 'v_' . $value->svinstrument_param_id ?>"><?= $value->voltage ?></span> <small class="text-dark">V</small></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
<?= $this->endSection() ?>

<!-- js -->
<?= $this->section('sectionjs') ?>
<script>
    $(document).ready(function() {
        setInterval(() => {
            $.ajax({
                url: `<?= base_url('getsensorvalue') ?>`,
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

<!-- alert js -->
<?= $this->include('Template/Alertjs') ?>

<?= $this->endSection() ?>
<!-- end content -->