<section class="container-fluid p-0">
  <div class="d-flex align-items-stretch">
    <div class="d-none d-sm-block col-2 navbar-dark bg-dark" style="height:100vh;">
      <nav class="nav flex-column navbar-dark bg-dark">
        <a class="nav-link active" href="/categories">Categorías</a>
        <a class="nav-link" href="/products">Productos</a>
        <a class="nav-link" href="#">Link</a>
        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
      </nav>
    </div>
    <div class="col-12 col-sm-10 p-3">
      <div class="glass rounded shadow p-3">
        <h1><i class="<?= W::fa('categories') ?>"></i> <?= _t('categories') ?></h1>
        <hr/>
        </div>
      </div>
    </div>
  </div>
</section>