<section class="container-fluid p-0">
  <div class="d-flex align-items-stretch">
    <div class="d-none d-sm-block col-2 navbar-dark bg-dark" style="min-height:95vh;">
      <?= W::company_menu() ?>
    </div>
    <div id="taskModel" class="col-12 col-sm-10 p-3">
      <div class="glass rounded shadow p-3">
        <div class="d-flex justify-content-between m-0">
          <h1><i class="<?= W::fa('tasks') ?>"></i> <?= _t('tasks') ?></h1>
          <div>
            <div id="mainAlert" class="alert alert-dismissible" style="display:none" role="alert">
              <div class="msg">MSG</div>
              <button data-bind="click: Collection.closeAlert" type="button" class="btn-close"></button>
            </div>
          </div>
          <div>
            <span data-bind="visible: 'edit'==state()">
              <button data-bind="click: setNew" class="btn btn-success"><i class="fa fa-plus"></i> NUEVA</button>
            </span>
          </div>
        </div>
        <hr class="m-0 mb-3"/>
        <div class="row">
          <div id="taskTable" class="col-8">
            <!-- TABLA -->
            <table class="table table-sm table-striped table-hover">
              <thead>
                <tr data-bind="foreach: table_columns">
                  <th data-bind="text: $data"></th>
                </tr>
              </thead>
              <tbody data-bind="foreach: {data: items, as: 'item'} ">
                <tr data-bind="foreach: {data: $parent.table_columns, as: 'name'}">
                  <td data-bind="
                    click: item.select.bind(item),
                    class: item.columns()[name].tdClass(),
                    "
                    class="cursor-pointer">
                    <span data-bind="text: item.columns()[name].table_value"></span>
                    </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-4">
            <div data-bind="with: newItem" id="taskForm"
              class="form-wrapper bg-dark text-light rounded p-3 shadow sticky-top">
              <h2 data-bind="visible: 'new'==collection.state()" class="pb-1">Crear Nueva Tarea</h2>
              <h2 data-bind="visible: 'edit'==collection.state()" class="pb-1">Modificar Tarea</h2>
              <!-- FORMULARIO -->
              <form data-bind="submit: Collection.dataSave" action="#" method="post">
                <div data-bind="foreach: {data: column_names, as: 'cn'}">
                  <div data-bind="with: $parent.columns()[cn]">
                    <div data-bind="template: fieldTemplate" class="row mb-md-1 g-2"></div>
                  </div>
                </div>
                <div class="text-end">
                  <span data-bind="visible: 'edit'==collection.state()">
                    <button data-bind="click: Collection.deleteMe" class="btn btn-danger">ELIMINAR</button>
                    <button _data-bind="click: Collection.save" class="btn btn-primary">GUARDAR</button>
                  </span>
                  <span data-bind="visible: 'new'==collection.state()">
                    <button _data-bind="click: Collection.save" class="btn btn-success"><i class="fa fa-plus"></i> GUARDAR</button>
                  </span>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<template id="inputTemplate">
  <label data-bind="text: cn, visible: 'hidden'!=type()"
    class="col-sm-3 col-form-label col-form-label-sm"></label>
  <div class="col-sm-9">
    <input data-bind="
      value: value,
      attr:{
        type: type,
        required: (options().required||null) ? true:false,
        step: type()=='number' ? options().step||false : false,
      },
      "
      type="text" class="form-control form-control-sm"/>
  </div>
</template>

<template id="linkTemplate">
  <label data-bind="text: cn, visible: 'hidden'!=type()"
    class="col-sm-3 col-form-label col-form-label-sm"></label>
  <div class="col-sm-9">
    <select data-bind="
      value: value,
      options: linkOptions,
      optionsText: 'name',
      optionsValue: 'id',
      optionsCaption: 'Seleccione...'
      "
      class="form-select form-select-sm"></select>
  </div>
</template>

<template id="textTemplate">
  <label data-bind="text: cn, visible: 'hidden'!=type()"
    class="col-sm-3 col-form-label col-form-label-sm"></label>
  <div class="col-sm-9">
    <textarea data-bind="value: value" rows="3"
      class="form-control form-control-sm"></textarea>
  </div>
</template>

<script>
(function() {

  const TASK_COLUMNS  = {
    id:{type: 'hidden'},
    date:{type:'date', required:1},
    time:{hide_col:true, type:'time'},
    contact_id:{},
    name:{required:1},
    category_id:{required:1},
    quantity:{type:'number', step: 'any', required:1, td: 'text-end'},
    uom_id:{required:1},
    price:{type:'number', step:'any', required:1, td: 'text-end'},
    description:{hide_col:true, type:'textarea'},
    notes:{hide_col:true, type:'textarea'},
  }

  const TASK_MESSAGES = {
    create: 'Nueva Tarea creada correctamente',
    update: 'Tarea modificada correctamente',
    delete: 'Tarea eliminada correctamente',
    are_you_sure: '¿Quieres continuar para eliminar esta Tarea?',
  }

  var collection = new Collection('/api/v1/tasks',{
    columns: TASK_COLUMNS,
    messages: TASK_MESSAGES,
  });
  $(function() {
    ko.applyBindings(collection, document.getElementById('taskModel'))
  })
}).call(this)
</script>