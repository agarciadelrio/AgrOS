<section class="container-fluid p-0">
  <div class="d-flex align-items-stretch">
    <div class="d-none d-sm-block col-2 navbar-dark bg-dark" style="min-height:95vh;">
      <?= W::company_menu() ?>
    </div>
    <div class="col-12 col-sm-10 p-3">
      <div class="glass rounded shadow p-3">
        <div id="mainHeader" data-bind="template: 'headerTemplate'" class="container-fluid p-0">
        </div>
        <hr class="m-0 mb-3"/>
        <section class="container-fluid p-0 m-0">
          <div class="row">
            <div class="col col-sm-4 col-md-7">
              <div id="mainTable" >
                <div class="table-responsive" data-bind="template: {name: 'tableTemplate', data: collection}"></div>
              </div>
            </div>
            <div class="col col-sm-8 col-md-5 pt-0">
              <div id="mainForm" class="bg-dark text-light shadow rounded pt-1">
                <header class="row px-3">
                  <h3 data-bind="text: formTitle" class="p-0 ps-2 col text-truncate"></h3>
                  <div data-bind="if: globalMsg" class="col-auto">
                    <div data-bind="text: globalMsg"
                      class="bg-warning p-0 px-2 py-1 m-0 mt-2 d-flex align-items-center shadow text-dark">TAL MENSAJE</div>
                  </div>
                </header>
                <form data-bind="submit: sendData, template: {name: 'formTemplate' , data: collection}" class="form m-0 p-0 pt-3">
                </form>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</section>

<script>
window.ICONS = <?= json_encode(ICONS) ?>;
console.log('ICONS', ICONS);
const product_form_columns = [
  '/date',
  ':time',
  'name!',
  '"description|250',
  '"notes|250',
  //'^uom_id',
  '#farm_id!',
  '$price!',
  //'+state!', '?buyable',
  '#contact_id!',
  '#plot_id!',
  '#category_id!',
  '#product_id!',
  '#category_id!',
  '#quantity||{step:0.01}',
  '#uom_id!',
  //'^user_id',
]

const product_list_columns = [
  '#=',
  //'id',
  'date',
  'name',
  'farm_id',
  'plot_id^',
  'category_id^',
  'quantity',
  'uom_id^',
  'price^',
  //'user_id^',
]

const app2 = new App2('/api/v1', 'task', product_form_columns, product_list_columns)
ko.applyBindings(app2, document.getElementById('mainTable'))
app2.loadData()
ko.applyBindings(app2, document.getElementById('mainForm'))
ko.applyBindings(app2, document.getElementById('mainHeader'))
</script>