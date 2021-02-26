<div class="container-fluid">
  <a href="/dashboard">Dashboard</a>
  <div id="exampleModal" class="glass rounded shadow p-3 my-3">
    <h1 class="d-flex justify-content-between">
      <span data-bind="text: name"><?= hs($task->name) ?></span>
      <span data-bind="text: isDirty"></span>
      <small data-bind="text: date">2021-01-01</small>
    </h1>
    <form action="/company/<? $company->id ?>/tasks" method="post">
      <div class=" row g-2">
        <input type="hidden" name="_method" value="create" readonly/>
        <? W::fields($form) ?>
      </div>
      <div class="text-end mt-3">
        <a href="javascript:history.back();" class="btn btn-warning" >Cancel</a>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
  </div>
</div>

<script defer>
(function(){
  var myModalEl = document.getElementById('exampleModal')
  task = new Task({id:<?= $task->id ?>, name:'',description:''});
  ko.applyBindings(task, myModalEl);
}).call(this);
</script>