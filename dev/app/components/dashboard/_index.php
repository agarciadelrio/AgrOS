<section class="container-fluid">
  <div class="glass rounded shadow p-3">
    <h1>Dashboard</h1>
    <code class="text-dark"><?= $ses ?></code>
    <?= $user->name ?>
    <hr/>
    <a href="/user">User</a>
    <a href="/contact">Contact</a>
    <a href="/category">Category</a>
    <a href="/uom">UOM</a>
    <a href="/product">Product</a>
    <div class="row">
      <div class="col">
        <h2>Empresas</h2>
        <ul>
          <? foreach ($user->ownCompanyList as $company): ?>
          <li><a href="/company/<?= $company->id ?>"><?= $company->name ?></a></li>
          <? endforeach; ?>
        </ul>
        <h2>Granjas</h2>
        <ul>
          <?php /*
          <? foreach ($user->ownFarmList as $farm): ?>
          <li>
            <a href="/company/<?= $farm->company->id ?>"><?= $farm->company->name ?></a>:
            <a href="/farm/<?= $farm->id ?>"><?= $farm->name ?></a>
          </li>
          <? endforeach; ?>
          */ ?>
        </ul>
      </div>
      <div class="col">
        <h2>Trabajos</h2>
        <ul>
          <?php /*
          <? foreach ($user->ownTaskList as $task): ?>
          <li>
            <a href="/farm/<?= $task->farm->id ?>"><?= $task->farm->name ?></a>:
            <a href="/task/<?= $task->id ?>"><?= $task->name ?></a>
          </li>
          <? endforeach; ?>
          */ ?>
        </ul>
      </div>
    </div>
  </div>
</section>