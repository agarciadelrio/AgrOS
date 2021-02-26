<section class="container-fluid">
  <div class="glass rounded shadow p-3">
    <h1>Profile</h1>
    <?php /*
    <code class="text-dark"><?= $ses ?></code>
    <?= $user->name ?>
    <hr/>
    */ ?>
    <form action="#" method="post" autocomplete="off" class="p-0">
      <div class="fields-wrapper">
          <div class="row">
            <div class="col-sm-6">
              <? W::fields(Profile::$form, ['horizontal' => true, 'label' => 'md-3', 'input' => 'md-9']) ?>
            </div>
            <div class="col-sm-6">
              <? W::fields(Profile::$form2, ['horizontal' => true, 'label' => 'md-3', 'input' => 'md-9']) ?>
            </div>
          </div>
      </div>
      <div class="text-end mt-3">
        <a href="javascript:history.back();" class="btn btn-warning" >Cancel</a>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
    <?php /*
    <form action="/profile" method="post" autocomplete="off" >
      <div class="row mb-3">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
          <input type="email" class="form-control" id="inputEmail3" autocomplete="off" value="">
        </div>
      </div>
      <div class="row mb-3">
        <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
        <div class="col-sm-10">
          <input type="password" class="form-control" id="inputPassword3" autocomplete="new-password" >
        </div>
      </div>
      <fieldset class="row mb-3">
        <legend class="col-form-label col-sm-2 pt-0">Radios</legend>
        <div class="col-sm-10">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="option1" checked>
            <label class="form-check-label" for="gridRadios1">
              First radio
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="option2">
            <label class="form-check-label" for="gridRadios2">
              Second radio
            </label>
          </div>
          <div class="form-check disabled">
            <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios3" value="option3" disabled>
            <label class="form-check-label" for="gridRadios3">
              Third disabled radio
            </label>
          </div>
        </div>
      </fieldset>
      <div class="row mb-3">
        <div class="col-sm-10 offset-sm-2">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="gridCheck1">
            <label class="form-check-label" for="gridCheck1">
              Example checkbox
            </label>
          </div>
        </div>
      </div>
      <div class="text-end">
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
    */ ?>
  </div>
</section>