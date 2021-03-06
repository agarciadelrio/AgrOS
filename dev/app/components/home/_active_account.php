<section class="d-flex flex-column justify-content-center align-items-center h-100 bg-agros">
  <h1 style="margin-top: -50px;">AgrOS</h1>
  <div class="glass rounded shadow p-3 mt-5">
    <h2 class="mt-3 mb-5">ACTIVAR CUENTA</h2>
    <form action="/active-account" method="post">
      <div class="mb-3">
        <label for="inpSessionEmail" class="form-label">Dirección de email</label>
        <input type="email" class="form-control" id="inpSessionEmail" name="session[email]" required>
        <div id="emailHelp" class="form-text">Por favor verifica tu dirección de correo electrónico.</div>
      </div>
      <div class="mb-3">
        <label for="inpSessionToken" class="form-label">Clave de verificación:</label>
        <span><?= $token ?></span>
        <input value="<?= $token ?>" type="hidden" class="form-control" id="inpSessionToken" name="session[token]" required readonly>
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
      <button type="submit" class="btn btn-primary w-100">Activar mi cuenta</button>
    </form>
  </div>
</section>