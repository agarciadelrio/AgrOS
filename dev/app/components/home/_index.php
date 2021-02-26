<div class="full-screen d-flex flex-column justify-content-center align-items-center">

  <img src="/app/assets/img/agros-logo.svg" style="max-width:300px;width:90%;" class="_mt-n5 shadow">
  <h1 class="display-1 mt-3">AgrOS</h1>
  <h2>v.<?= $version ?></h2>
  <nav class="d-flex flex-row">
    <?= renderMenu(MENUS['main']) ?>
  </nav>
</div>

<? /*
<section class="container-fluid">
  <div class="glass rounded shadow p-3">
    <h1><?= APP_NAME ?> <small><?= $version ?></small></h1>
    <a href="/login">Login</a>
    <a href="/logout">logout</a>
    <a href="/register">register</a>
    <a href="/recover">recover</a>
    <hr/>
    <? $menu=['user','company','farm','parcel','plot']; foreach($menu as $m): ?>
    <a href="/<?= $m ?>"><?= $m ?></a><? endforeach; ?><hr/>
    <? $menu=['category','product','contact','uom']; foreach($menu as $m): ?>
    <a href="/<?= $m ?>"><?= $m ?></a><? endforeach; ?><hr/>
    <? $menu=['plot','task']; foreach($menu as $m): ?>
    <a href="/<?= $m ?>"><?= $m ?></a><? endforeach; ?><hr/>
  </div>
</section>
*/ ?>