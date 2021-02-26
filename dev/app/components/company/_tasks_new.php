<section class="container-fluid">

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Launch demo modal 1
</button>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2">
  Launch demo modal 2
</button>

<!-- Modal 1-->
<div class="modal fade" id="exampleModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <form class="modal-content glass rounded shadow" action="/company/<? $company->id ?>/tasks" method="post">
      <div class="modal-header bg-info">
        <h5 data-bind="text: name" class="modal-title">Modal title 1</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body row g-2">
        <input type="hidden" name="_method" value="create" readonly/>
        <? W::fields($form) ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2">
          Launch M2
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal 2-->
<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModal2Label" aria-hidden="true">
  <div class="modal-dialog modal-xxl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModal2Label">Modal title 2</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
</section>

<script defer>
(function(){
  var myModalEl = document.getElementById('exampleModal')
  var task = null;
  myModalEl.addEventListener('show.bs.modal', function (event) {
    task = new Task({id:2, name:'',description:''});
    ko.applyBindings(task, myModalEl);
  })
  myModalEl.addEventListener('hidden.bs.modal', function (event) {
    ko.cleanNode(this);
    delete task;
  });

  var myModal = new bootstrap.Modal(myModalEl, {
    keyboard: true
  });

  myModal.show();
}).call(this);
</script>
