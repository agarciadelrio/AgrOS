<?
  $label_col = $label_col ?? 2;
  $input_col = 12 - $label_col;
?>

<template id="headerTemplate">
  <div class="row">
    <div class="col d-flex align-items-center">
        <h1 class="m-0 p-0 me-2">
          <i data-bind="attr: {class:`fa fa-fw fa-${window.ICONS[plural]||'cog'}`}"></i>
          <span data-bind="translate: plural">Model</span>
        </h1>
        <div class="dropdown">
          <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" data-bs-toggle="dropdown">
            <i class="<?= W::fa('wrench') ?>"></i>
          </a>
          <nav class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="#"><i class="<?= W::fa('print') ?>"></i> Imprimir</a>
            <a data-bind="click: collection.setNew.bind(collection)" class="dropdown-item" href="#"><i class="<?= W::fa('plus') ?>"></i> Nuevo</a>
            <a class="dropdown-item" href="#"><i class="<?= W::fa('clone') ?>"></i> Duplicar</a>
            <a data-bind="click: collection.deleteMe" class="dropdown-item link-danger" href="#"><i class="<?= W::fa('trash') ?>"></i> Eliminar</a>
          </nav>
        </div>
    </div>
    <div class="col-auto">
      <div class="input-group input-group-sm">
        <!--input type="text" style="max-width:5em;" class="form-control text-center" placeholder="Recipient's username" value="1-13"/-->
        <span class="input-group-text"><span data-bind="text: page">32</span></span>
        <span class="input-group-text">/</span>
        <span class="input-group-text"><span data-bind="text: total_pages() + 1">32</span></span>
        <button data-bind="click: prevPage" class="btn btn-info" type="button"><i class="<?= W::fa('chevron-left') ?>"></i></button>
        <button data-bind="click: nextPage" class="btn btn-info" type="button"><i class="<?= W::fa('chevron-right') ?>"></i></button>
      </div>
    </div>
  </div>
</template>

<template id="tableTemplate">
    <table class="table table-sm table-hover table-striped _table-bordered">
        <thead>
            <tr data-bind="foreach: columns">
                <th data-bind="attr: {class: $data.class}"><span data-bind="template: thTpl"></span></th>
            </tr>
        </thead>
        <tbody data-bind="foreach: {data: records, as: 'record'}">
            <tr data-bind="click: selectRow,foreach: $parent.columns, css:{'table-success': $parent.selectedRecord()==$data}">
                <td data-bind="template: tdTpl, attr: {class: $data.class}"></td>
            </tr>
        </tbody>
    </table>
</template>

<template id="tdSelTemplate">
  <button class="btn btn-sm btn-link p-0">
    <i data-bind="visible: record.selected()" class="fa fa-fw fa-check-circle"></i>
    <i data-bind="visible: !record.selected()" class="fa fa-fw fa-circle-o"></i>
  </button>
  <!--input data-bind="checked: record.selected()" type="checkbox"-->
</template>

<template id="tdIdTemplate">
  <span data-bind="text: record.id"></span>
</template>

<template id="tdTemplate">
  <span data-bind="text: (record.fields()[name]||{}).value"></span>
</template>

<template id="thTemplate">
  <span data-bind="text: $data.name"></span>
</template>

<template id="thSelTemplate">
  <input type="checkbox">
</template>

<template id="formTemplate">
    <div class="container" data-bind="foreach: fields_format">
        <div class="row mb-1" data-bind="template: `${type}Template`"></div>
    </div>
    <div class="container mt-3">
        <div class="row">
            <div class="col-12 col-sm-6 order-sm-last">
              <div class="mb-3 text-end">
                <button class="btn btn-primary mb-2" type="submit"><i class="fa fa-fw fa-upload"></i> Save item</button>
              </div>
            </div>
            <div class="col-12 col-sm-6 order-sm-first">
              <div data-bind="visible: $root.editing" class="dropdown">
                <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" data-bs-toggle="dropdown">
                  <i class="<?= W::fa('wrench') ?>"></i> Acciones
                </a>
                <nav class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                  <a data-bind="click: setNew" class="dropdown-item" href="#"><i class="<?= W::fa('plus') ?>"></i> Nuevo</a>
                  <a class="dropdown-item" href="#"><i class="<?= W::fa('clone') ?>"></i> Duplicar</a>
                  <hr/>
                  <a data-bind="click: deleteMe" class="dropdown-item text-danger" href="#"><i class="<?= W::fa('trash') ?>"></i> Eliminar</a>
                </nav>
              </div>
            </div>
        </div>
    </div>
</template>

<template id="numberTemplate">
  <label class="text-start text-sm-end col-form-label-sm col-sm-<?= $label_col ?> col-form-label" data-bind="translate: name, attr:{for: _id}"></label>
  <div class="col-sm-<?= $input_col ?>">
    <input class="form-control-sm form-control" type="number"
    data-bind="value: value, attr: attr">
  </div>
</template>

<template id="currencyTemplate">
  <label class="text-start text-sm-end col-form-label-sm col-sm-<?= $label_col ?> col-form-label" data-bind="translate: name, attr:{for: _id}"></label>
  <div class="col-sm-<?= $input_col ?>">
    <input class="form-control-sm form-control" type="number" step="0.01"
    data-bind="value: value, attr: attr">
  </div>
</template>

<template id="textareaTemplate">
  <label class="text-start text-sm-end col-form-label-sm col-sm-<?= $label_col ?> col-form-label" data-bind="translate: name, attr:{for: _id}"></label>
  <div class="col-sm-<?= $input_col ?>"><textarea class="form-control-sm form-control" data-bind="value: value, attr: attr"></textarea></div>
</template>

<template id="passwordTemplate">
  <label class="text-start text-sm-end col-form-label-sm col-sm-<?= $label_col ?> col-form-label" data-bind="translate: name, attr:{for: _id}"></label>
  <div class="col-sm-<?= $input_col ?>"><input class="form-control-sm form-control" type="password" data-bind="value: value, attr: attr"></div>
</template>

<template id="dateTemplate">
  <label class="text-start text-sm-end col-form-label-sm col-sm-<?= $label_col ?> col-form-label" data-bind="translate: name, attr:{for: _id}"></label>
  <div class="col-sm-<?= $input_col ?>"><input class="form-control-sm form-control" type="date" data-bind="value: value, attr: attr"></div>
</template>

<template id="timeTemplate">
  <label class="text-start text-sm-end col-form-label-sm col-sm-<?= $label_col ?> col-form-label" data-bind="translate: name, attr:{for: _id}"></label>
  <div class="col-sm-<?= $input_col ?>"><input class="form-control-sm form-control" type="time" data-bind="value: value, attr: attr"></div>
</template>

<template id="linkTemplate">
  <label class="text-start text-sm-end col-form-label-sm col-sm-<?= $label_col ?> col-form-label" data-bind="translate: name, attr:{for: _id}"></label>
  <div class="col-sm-<?= $input_col ?>">
    <select class="form-select-sm form-select" data-bind="attr: attr">
      <option>SELECCIONE...</option>
    </select>
  </div>
</template>

<template id="collectionTemplate">
  <label class="text-start text-sm-end col-form-label-sm col-sm-<?= $label_col ?> col-form-label" data-bind="translate: name, attr:{for: _id}"></label>
  <div class="col-sm-<?= $input_col ?>">
    <table class="table table-sm text-light">
      <thead>
        <tr>
          <th>id</th>
          <th>name</th>
          <th>tool</th>
        </tr>
      </thead>
    </table>
  </div>
</template>

<template id="selectTemplate">
  <label class="text-start text-sm-end col-form-label-sm col-sm-<?= $label_col ?> col-form-label" data-bind="translate: name, attr:{for: _id}"></label>
  <div class="col-sm-<?= $input_col ?>">
    <select class="form-select-sm form-select" data-bind="attr: attr">
      <option>SELECCIONE...</option>
    </select>
  </div>
</template>

<template id="checkboxTemplate">
    <div class="col-sm-<?= $label_col ?> col-form-label"></div>
    <div class="col-sm-<?= $input_col ?>">
      <input type="checkbox" data-bind="checked: value, attr: attr">
      <label class="col-form-label-sm ms-2 col-sm-<?= $label_col ?> col-form-label" data-bind="translate: name, attr:{for: _id}"></label>
    </div>
</template>

<template id="radioTemplate">
    <div class="col-sm-<?= $label_col ?> col-form-label"></div>
    <div class="col-sm-<?= $input_col ?>">
      <input type="radio" data-bind="checked: value, attr: attr">
      <label class="col-form-label-sm ms-2 col-sm-<?= $label_col ?> col-form-label" data-bind="translate: name, attr:{for: _id}"></label>
    </div>
</template>

<template id="textTemplate">
  <label class="text-start text-sm-end col-form-label-sm col-sm-<?= $label_col ?> col-form-label" data-bind="translate: name, attr:{for: _id}"></label>
    <div class="col-sm-<?= $input_col ?>">
      <input class="form-control-sm form-control" type="text" data-bind="value: value, attr: attr">
    </div>
</template>

<template id="hiddenTemplate">
  <input type="hidden" data-bind="value: value">
</template>

<template id="emailTemplate">
  <label class="text-start text-sm-end col-form-label-sm col-sm-<?= $label_col ?> col-form-label" data-bind="translate: name, attr:{for: _id}"></label>
  <div class="col-sm-<?= $input_col ?>">
    <input class="form-control-sm form-control" type="email" data-bind="value: value, attr: attr">
  </div>
</template>

<template id="telTemplate">
  <label class="text-start text-sm-end col-form-label-sm col-sm-<?= $label_col ?> col-form-label" data-bind="translate: name, attr:{for: _id}"></label>
  <div class="col-sm-<?= $input_col ?>">
    <input class="form-control-sm form-control" type="tel" data-bind="value: value, attr: attr">
  </div>
</template>

<template id="fileTemplate">
  <label class="text-start text-sm-end col-form-label-sm col-sm-<?= $label_col ?> col-form-label" data-bind="translate: name, attr:{for: _id}"></label>
  <div class="col-sm-<?= $input_col ?>">
    <input class="form-control-sm form-control" type="file" data-bind="value: value, attr: attr">
  </div>
</template>

<template id="rangeTemplate">
  <label class="text-start text-sm-end col-form-label-sm col-sm-<?= $label_col ?> col-form-label" data-bind="translate: name, attr:{for: _id}"></label>
  <div class="col-sm-8">
    <input class="form-control-sm form-range" type="range" data-bind="value: value, attr: attr">
  </div>
  <div class="col-sm-2">
    <span class="form-control-sm form-control" data-bind="text: value"></span>
  </div>
</template>