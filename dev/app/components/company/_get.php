<div class="container-fluid">
  <div class="glass rounded shadow p-3">
    <h1>Company GET</h1>
    <a href="/dashboard">Dashboard</a>
    <hr/>
    <?= hs($company->name) ?>
    <h2>Granjas</h2>  <i class="fa fa-cog"></i>
    <ul>
      <? foreach ($company->sharedFarmList as $farm): ?>
      <li><a href="/farm/<?= $farm->id ?>"><?= hs($farm->name) ?></a></li>
      <? endforeach; ?>
    </ul>
    <div class="row">
      <div class="col-sm">
        <table id="userTaskTable" class="table"
          data-src="users/<?= $company->id ?>"
          data-limit="15"
          data-set="tasks"
          data-order="">
            <caption>
              <div class="d-flex justify-content-between">
                <div class="title" data-bind="text: title">Cargando...</div>
                <div class="nav">
                  <div class="btn-group btn-group-sm" role="group" aria-label="Button group">
                  <button data-bind="click: prevPage" type="button" class="btn btn-primary">&lt;</button>
                  <input data-bind="value: page"/>
                  <input data-bind="value: limit"/>
                  <button data-bind="click: nextPage" type="button" class="btn btn-primary">&gt;</button>
                  </div>
                </div>
              </div>
            </caption>
            <thead>
              <tr>
                <th><input type="checkbox"/></th>
                <th data-bind="click: ord.bind('id')">ID</th>
                <th data-bind="click: ord.bind('name')">Name</th>
                <th data-bind="click: ord.bind('date')">Date</th>
                <th></th>
              </tr>
            </thead>
            <tbody data-bind="foreach: items">
              <tr>
                <td><input type="checkbox"/></td>
                <td data-bind="text: id">0</td>
                <td data-bind="text: name"></td>
                <td data-bind="text: date">Cargando datos...</td>
                <td></td>
              </tr>
            </tbody>
        </table>
      </div>
      <div class="col-sm">
        <table id="companyFarmTable" class="table"
          data-src="companies/<?= $company->id ?>"
          data-limit="15"
          data-set="farms">
          <caption>
              <div class="d-flex justify-content-between">
                <div class="title" data-bind="text: title">Cargando...</div>
                <div class="nav">
                  <div class="btn-group btn-group-sm" role="group" aria-label="Button group">
                  <button data-bind="click: prevPage" type="button" class="btn btn-primary">&lt;</button>
                  <input data-bind="value: page"/>
                  <input data-bind="value: limit"/>
                  <button data-bind="click: nextPage" type="button" class="btn btn-primary">&gt;</button>
                  </div>
                </div>
              </div>
            </caption>
            <thead>
              <tr>
                <th><input type="checkbox"/></th>
                <th data-bind="click: ord.bind('id')">ID</th>
                <th data-bind="click: ord.bind('name')">Name</th>
                <th></th>
              </tr>
            </thead>
            <tbody data-bind="foreach: items">
              <tr>
                <td><input type="checkbox"/></td>
                <td data-bind="text: id"></td>
                <td data-bind="text: name">Cargando datos...</td>
                <td></td>
              </tr>
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>