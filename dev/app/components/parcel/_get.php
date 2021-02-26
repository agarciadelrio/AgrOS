<div class="container-fluid">
  <div class="glass rounded shadow p-3">
    <h1>Parcel GET</h1>
    <a href="/dashboard">Dashboard</a>
    <hr/>
    <?= hs($parcel->name) ?>
    <h2>Marcos</h2>
    <ul>
      <? foreach ($parcel->ownPlotList as $plot): ?>
      <li><a href="/plot/<?= $plot->id ?>"><?= hs($plot->name) ?></a></li>
      <? endforeach; ?>
    </ul>
  </div>
</div>