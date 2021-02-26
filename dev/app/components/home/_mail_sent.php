<section class="d-flex flex-column justify-content-center align-items-center h-100 bg-agros">
  <h1 style="margin-top: -50px;">AgrOS</h1>
  <div class="glass rounded shadow p-3 mt-5" style="max-width: 400px">
    <h2 class="mt-3 mb-5"><?= $title ?? 'AgrOS'?></h2>
    <form action="/recover" method="post">
      <div class="mb-3 text-center">
        <p>Gracias por usar AgrOS.</p>
        <p><?= hs($message) ?></p>
        <p>Esperamos verte pronto.</p>
      </div>
      <div class="mb-3 form-check p-0">
        <!--
        <input type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1">Check me out</label>
        -->
        <nav class="d-flex flex-row justify-content-center">
          <?= renderMenu(MENUS['recover']) ?>
        </nav>
        <nav class="d-flex flex-row justify-content-center">
          <?= renderMenu(MENUS['home']) ?>
        </nav>
      </div>
      <button type="submit" class="btn btn-primary w-100">Share me!</button>
    </form>
  </div>
</section>