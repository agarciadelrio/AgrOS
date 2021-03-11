<section class="container-fluid my-3">
  <div class="glass rounded shadow p-3">
    <?= W::list_header('dashboard') ?>
    <hr class="mb-3"/>
    <div class="row">
      <div class="col-12 col-sm-4 col-md-3">
        <section id="calendarApp" class="d-none d-sm-block calendar" data-bind="with: calendar,">
          <?= W::calendars() ?>
        </section>
        <select class="d-sm-none form-select form-select-sm mb-3">
          <option value="">Seleccione fecha</option>
        </select>
      </div>
      <div class="col-12 col-sm-8 col-md-9">
        <div id="taskModel" class="table-responsive">
          <?= W::table() ?>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
(function() {
  const TASK_COLUMNS  = {
    id:{hide_col:true, type: 'hidden'},
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
    order: 'date:2',
  });
  const app = new App()
  window.mainList = collection;

  $(function(){
    ko.applyBindings(app, document.getElementById('calendarApp'))
    ko.applyBindings(collection, document.getElementById('taskModel'))
  })
}).call(this);
</script>