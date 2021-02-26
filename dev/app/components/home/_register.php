<section class="d-flex flex-column justify-content-center align-items-center h-100 bg-agros">
  <h1 style="margin-top: -50px;">AgrOS</h1>
  <div class="glass rounded shadow p-3 mt-5">
    <h2 class="mt-3 mb-5">REGISTER</h2>
    <form action="/register" method="post">
      <div class="mb-3">
        <label for="inpSessionEmail" class="form-label">Email address</label>
        <input type="email" class="form-control" id="inpSessionEmail" name="session[email]" required>
        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
      </div>
      <div class="mb-3">
        <label for="inpSessionPassword" class="form-label">Password</label>
        <input type="password" class="form-control" id="inpSessionPassword" name="session[password]" required onChange="onChange()">
      </div>
      <div class="mb-3">
        <label for="inpSessionPassword" class="form-label">Password confirm</label>
        <input type="password" class="form-control" id="inpSessionPasswordConfirm" name="session[password_confirm]" required onChange="onChange()">
      </div>
      <div class="mb-3 text-center">
        <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
        <label class="form-check-label" for="exampleCheck1"><small>
          Yes, I accept the <a href="/legal" target="_blank">terms and conditions.</a>
        </small></label>
      </div>
      <div class="mb-3 p-0">
        <nav class="d-flex flex-row justify-content-center">
          <?= renderMenu(MENUS['register']) ?>
        </nav>
        <nav class="d-flex flex-row justify-content-center">
          <?= renderMenu(MENUS['home']) ?>
        </nav>
      </div>
      <button type="submit" class="btn btn-primary w-100">Sign in me</button>
    </form>
  </div>
</section>

<script defer>
function onChange() {
  const password = document.getElementById('inpSessionPassword');
  const confirm = document.getElementById('inpSessionPasswordConfirm');
  if (confirm.value === password.value) {
    confirm.setCustomValidity('');
  } else {
    confirm.setCustomValidity('Las contraseñas no coinciden');
  }
}
</script>