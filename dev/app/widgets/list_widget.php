<?php

Widget::register('list_actions', function($p1=[]) { ?>
<div class="dropdown">
  <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" data-bs-toggle="dropdown">
    <i class="<?= W::fa('wrench') ?>"></i> Acciones
  </a>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
    <li><a class="dropdown-item" href="/pdf/notebook" target="_blank"><i class="<?= W::fa('file-pdf') ?>"></i> PDF</a></li>
    <li><a class="dropdown-item" href="#">Eliminar</a></li>
    <li><a class="dropdown-item" href="#">Duplicar</a></li>
  </ul>
</div>
<?php });

Widget::register('list_nav', function($p1=[]) { ?>
<div class="input-group input-group-sm">
  <input type="text" style="max-width:5em;" class="form-control text-end" placeholder="Recipient's username" value="1/12"/>
  <span class="input-group-text">/32</span>
  <button onClick="mainList.prev()" class="btn btn-info" type="button"><i class="<?= W::fa('chevron-left') ?>"></i></button>
  <button onClick="mainList.next()" class="btn btn-info" type="button"><i class="<?= W::fa('chevron-right') ?>"></i></button>
</div>
<?php });

Widget::register('list_header', function($p1='Listado') {
  $title = $p1[0] ?? 'Listado' ?>
<header class="d-flex justify-content-between align-items-center p-0 mb-3">
  <h1><i class="<?= W::fa($title) ?>"></i> <?= _t($title) ?></h1>
  <div class="actions">
    <?= W::list_actions() ?>
  </div>
  <div class="">
  <?= W::list_nav() ?>
  </div>
</header>
<?php });