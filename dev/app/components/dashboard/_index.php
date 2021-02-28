<section class="container-fluid mt-3">
  <div class="glass rounded shadow p-3">
    <h1><i class="<?= W::fa('dashboard') ?>"></i> <?= _t('dashboard') ?></h1>
    <hr/>
    <div class="row">
      <div class="col-3">
        <section id="calendarApp"  class="calendar" data-bind="with: calendar,">
          <div class="months d-flex flex-column" data-bind="foreach: {data: months, as: 'M'}, css: css">
            <div class="month">
              <table>
                <thead>
                  <tr>
                    <th colspan="100%" data-bind="text: name"></th>
                  </tr>
                  <tr data-bind="foreach: Month.wdays">
                    <th data-bind="text: label"></th>
                  </tr>
                </thead>
                <tbody data-bind="foreach: weeks">
                  <tr data-bind="foreach: {data: $data, as: 'd'}">
                    <td data-bind="text: d.day, css: d.css">1</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
      <div class="col-9">
        <div id="taskModel">
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
      </div>
    </div>
  </div>
</section>

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

  const collection = new Collection('/api/v1/tasks',{
    columns: TASK_COLUMNS,
    messages: TASK_MESSAGES,
  });
  const app = new App()

  $(function(){
    ko.applyBindings(app, document.getElementById('calendarApp'))
    ko.applyBindings(collection, document.getElementById('taskModel'))
  })
}).call(this);
</script>