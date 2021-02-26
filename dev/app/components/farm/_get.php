<div class="container-fluid">
  <div class="glass rounded shadow p-3">
    <h1>Farm GET</h1>
    <a href="/dashboard">Dashboard</a>
    <hr/>
    <?= hs($farm->name) ?>
    <h2>Parcelas</h2>
    <ul>
      <? foreach ($farm->ownParcelList as $parcel): ?>
      <li><a href="/parcel/<?= $parcel->id ?>"><?= hs($parcel->name) ?></a></li>
      <? endforeach; ?>
    </ul>
  </div>
</div>