<header class="p-3 bg-secondary text-dark shadow-sm p-3 mb-5">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="<?= base_url('/') ?>" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <img src="<?= base_url('assets/logo/trudas.png') ?>" width="120" height="32" alt="TRUDAS">
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="<?= base_url('/') ?>" class="nav-link px-2  <?= uri_string() == '/' ? 'text-dark' : 'text-white' ?>">Data Acquisition System</a></li>
                <?php if (session('session_id')) : ?>
                    <li><a href="<?= base_url('data-log') ?>" class="nav-link px-2 <?= uri_string() == 'data-log' ? 'text-dark' : 'text-white' ?>">Das Log</a></li>
                    <li><a href="<?= base_url('configuration') ?>" class="nav-link px-2  <?= uri_string() == 'configuration' ? 'text-dark' : 'text-white' ?>">Configuration</a></li>
                    <li><a href="<?= base_url('sensor') ?>" class="nav-link px-2  <?= uri_string() == 'sensor' ? 'text-dark' : 'text-white' ?>">Sensor</a></li>
                    <li><a href="<?= base_url('reference') ?>" class="nav-link px-2  <?= uri_string() == 'reference' ? 'text-dark' : 'text-white' ?>">Reference</a></li>
                    <li><a href="<?= base_url('unit') ?>" class="nav-link px-2  <?= uri_string() == 'unit' ? 'text-dark' : 'text-white' ?>">Unit</a></li>
                    <li><a href="<?= base_url('user') ?>" class="nav-link px-2  <?= uri_string() == 'user' ? 'text-dark' : 'text-white' ?>">User</a></li>
                <?php endif ?>
            </ul>

            <div class="text-end">
                <?php if (session('session_id')) : ?>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-light me-2 btn-sm"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                <?php else : ?>
                    <a href="<?= base_url('login') ?>" class="btn btn-outline-light me-2 btn-sm"><i class="fas fa-sign-in-alt me-2"></i> Login</a>
                <?php endif ?>
            </div>
        </div>
    </div>
</header>