<section class="d-flex flex-column justify-content-center align-items-center h-100 bg-agros">
  <h1 style="margin-top: -50px;">AgrOS</h1>
  <div class="glass rounded shadow p-3 mt-5" style="min-width: 300px">
    <h2 class="mt-3 mb-5">REESTABLECER<br/>CONTRASEÑA</h2>
    <form action="/set-new-password" method="post">
      <input type="hidden" name="token" value="<?= $token ?>" />
      <div class="mb-3">
        <label for="inpSessionEmail" class="form-label">Email address</label>
        <input type="email" class="form-control" id="inpSessionEmail" name="session[email]" required>
        <div id="emailHelp" class="form-text">Verifique su correo electrónico.</div>
      </div>
      <div class="mb-3">
        <label for="inpSessionPassword" class="form-label">Password</label>
        <input type="password" class="form-control" id="inpSessionPassword" name="session[password]" required onChange="onChange()">
      </div>
      <div class="mb-3">
        <label for="inpSessionPassword" class="form-label">Password confirm</label>
        <input type="password" class="form-control" id="inpSessionPasswordConfirm" name="session[password_confirm]" required onChange="onChange()">
      </div>
      <button type="submit" class="btn btn-primary w-100 mt-3">Reestablecer y acceder</button>
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