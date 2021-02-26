<div class="container-fluid">
  <div class="glass rounded shadow p-3">
    <h1>Plot GET</h1>
    <a href="/dashboard">Dashboard</a>
    <hr/>
    <?= $plot->name ?>
    <h2>Trabajos</h2>
    <ul>
      <? foreach ($plot->ownTaskList as $task): ?>
      <li><a href="/task/<?= $task->id ?>"><?= $task->name ?></a></li>
      <? endforeach; ?>
    </ul>
  </div>
</div>