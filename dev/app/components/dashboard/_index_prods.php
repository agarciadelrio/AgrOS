<div class="container-fluid pt-2">
  <?= W::list_header('products') ?>
  <hr>
</div>
<section class="container-fluid mb-3">
  <div class="row">
    <div class="col col-sm-4 col-md-6">
      <div id="mainTable" >
        <div class="table-responsive" data-bind="template: {name: 'tableTemplate', data: collection}"></div>
      </div>
    </div>
    <div class="col col-sm-8 col-md-6">
      <div id="mainForm" class="glass rounded shadow p-3">
        <h2 data-bind="text: formTitle"></h2>
        <form class="form bg-dark text-light pt-3"
          data-bind="template: {name: 'formTemplate' , data: collection}"></form>
      </div>
    </div>
  </div>
</section>

<script>
const product_form_columns = [
  'name!', 'code|16!', '"description|250',
  '^uom_id', '#price!',
  '+state!', '?buyable',
  '^category_id!', '^user_id',
]

const product_list_columns = [
  '#=',
  //'id',
  'name','code=','price^',
  'category_id^','user_id^','uom_id^',
]

const app2 = new App2('/api/v1', 'product', product_form_columns, product_list_columns)
ko.applyBindings(app2, document.getElementById('mainTable'))
app2.loadData()
ko.applyBindings(app2, document.getElementById('mainForm'))
</script>