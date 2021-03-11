<?php

Widget::register('list_actions', function($p1=[]) { ?>
<div class="actions d-flex">
  <div class="dropdown me-2">
    <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" data-bs-toggle="dropdown">
      <i class="<?= W::fa('print') ?>"></i> Imprimir
    </a>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
      <li><a class="dropdown-item" href="/pdf/notebook" target="_blank"><i class="<?= W::fa('file-pdf') ?>"></i> PDF</a></li>
    </ul>
  </div>
  <div class="dropdown">
    <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" data-bs-toggle="dropdown">
      <i class="<?= W::fa('wrench') ?>"></i> Acciones
    </a>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
      <li><a class="dropdown-item" href="#">Nuevo</a></li>
      <li><a class="dropdown-item" href="#">Duplicar</a></li>
      <li><a class="dropdown-item" href="#">Eliminar</a></li>
    </ul>
  </div>
</div>
<?php });

Widget::register('list_nav', function($p1=[]) { ?>
<div class="input-group input-group-sm">
  <input type="text" style="max-width:5em;" class="form-control text-center" placeholder="Recipient's username" value="1-12"/>
  <span class="input-group-text">/&nbsp;32</span>
  <button onClick="mainList.prev()" class="btn btn-info" type="button"><i class="<?= W::fa('chevron-left') ?>"></i></button>
  <button onClick="mainList.next()" class="btn btn-info" type="button"><i class="<?= W::fa('chevron-right') ?>"></i></button>
</div>
<?php });

Widget::register('list_header', function($p1='Listado') {
  $title = $p1[0] ?? 'Listado' ?>
<header class="row justify-content-between align-items-center p-0 mb-3">
  <h1 class="col-12 col-sm-auto text-begin"><i class="<?= W::fa($title) ?>"></i> <?= _t($title) ?></h1>
  <div class="col-12 col-sm-auto actions mb-3 mb-md-0 text-center">
    <?= W::list_actions() ?>
  </div>
  <div class="col-12 col-sm-auto text-end">
    <?= W::list_nav() ?>
  </div>
</header>
<?php });

Widget::register('table', function($p){
?>
<table class="table table-sm table-striped table-hover">
  <thead>
    <tr>
      <th><input type="checkbox"></th>
      <!-- ko foreach: table_columns -->
      <th data-bind="text: $data"></th>
      <!-- /ko -->
    </tr>
  </thead>
  <tbody data-bind="foreach: {data: items, as: 'item'} ">
    <tr>
      <td><input type="checkbox"></td>
      <!-- ko foreach: {data: $parent.table_columns, as: 'name'} -->
      <td data-bind="
        click: item.select.bind(item),
        class: item.columns()[name].tdClass(),
        "
        class="cursor-pointer">
        <span data-bind="text: item.columns()[name].table_value"></span>
      </td>
      <!-- /ko -->
    </tr>
  </tbody>
</table>
<?php });