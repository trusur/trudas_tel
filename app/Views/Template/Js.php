<script src="<?= base_url('assets/bootstrap-5.1.3/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= base_url('assets/fontawesome/js/all.min.js') ?>"></script>

<script>
    function clock_tick() {
        let now = new Date();
        $("#datetime").html(("00" + now.getDate()).slice(-2) + "/" + ("00" + (now.getMonth() + 1)).slice(-2) + "/" + (now.getFullYear()) + " " + ("00" + now.getHours()).slice(-2) + ":" + ("00" + now.getMinutes()).slice(-2) + ":" + ("00" + now.getSeconds()).slice(-2));
        setTimeout(function() {
            clock_tick();
        }, 1000);
    }
    clock_tick();
</script>