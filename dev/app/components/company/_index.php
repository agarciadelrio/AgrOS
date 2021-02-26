<section class="container-fluid p-0">
  <div class="d-flex align-items-stretch">
    <div class="d-none d-sm-block col-2 navbar-dark bg-dark" style="height:100vh;">
      <nav class="nav flex-column navbar-dark bg-dark">
        <a class="nav-link active" href="/companies"><?= _t('companies') ?></a>
        <a class="nav-link" href="/farms"><?= _t('farms') ?></a>
        <a class="nav-link" href="/parcels"><?= _t('parcels') ?></a>
        <a class="nav-link" href="/plots"><?= _t('plots') ?></a>
        <a class="nav-link" href="/tasks"><?= _t('tasks') ?></a>
      </nav>
    </div>
    <div class="col-12 col-sm-10 p-3">
      <div class="glass rounded shadow p-3">
        <h1><i class="<?= W::fa('companies') ?>"></i> <?= _t('companies') ?></h1>
        <hr/>
        </div>
      </div>
    </div>
  </div>
</section>