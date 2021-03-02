<section class="container-fluid mt-3">
  <div class="glass rounded shadow p-3">
    <header class="d-flex justify-content-between align-items-center p-0 mb-3">
      <h1 class="p-0 m-0"><i class="<?= W::fa('dashboard') ?>"></i> <?= _t('dashboard') ?></h1>
      <div class="actions">
        <a href="/pdf/notebook" target="_blank">PDF</a>
      </div>
    </header>
    <hr class="mb-3"/>
    <div class="row">
      <div class="col-3">
        <section id="calendarApp"  class="calendar" data-bind="with: calendar,">
          <?= W::calendars() ?>
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