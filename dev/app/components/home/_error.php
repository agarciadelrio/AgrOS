<section class="d-flex flex-column justify-content-center align-items-center h-100 bg-agros">
  <h1 style="margin-top: -50px;">AgrOS</h1>
  <div class="glass rounded shadow p-3 m-3" style="max-width: 500px">
    <h2 class="mt-3 mb-5"><?= hs($title ?? 'ERROR') ?></h2>
    <form action="/recover" method="post">
      <div class="mb-3 text-center">
        <p>Ops! Parece que algo ha salido mal.</p>
        <p><?= $message ?></p>
        <p>Vuelve a intentarlo en otro momento o contacta con el
          <a href="mailto:admin@agros.jaira.com" target="_blank">administrador</a>.</p>
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
          <?= renderMenu(MENUS['back_home']) ?>
        </nav>
      </div>
    </form>
  </div>
</section>