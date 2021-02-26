<section class="container-fluid mt-3">
  <div class="glass rounded shadow p-3">
    <h1><i class="fa fa-fw fa-address-card"></i> Profile <?= hs($user->name) ?></h1>
    <hr/>
    <form action="#" method="post" autocomplete="off" class="mt-4 p-0">
      <div class="fields-wrapper">
          <div class="row">
            <div class="col-sm-6">
              <? W::fields(Profile::$form, ['horizontal' => true, 'label' => 'md-3', 'input' => 'md-9'], $user) ?>
            </div>
            <div class="col-sm-6">
              <? W::fields(Profile::$form2, ['horizontal' => true, 'label' => 'md-3', 'input' => 'md-9'], $user) ?>
            </div>
          </div>
      </div>
      <div class="text-end mt-3">
        <a href="/dashboard" class="btn btn-warning" >Cancel</a>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
  </div>
</section>