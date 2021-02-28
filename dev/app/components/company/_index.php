<section class="container-fluid p-0">
  <div class="d-flex align-items-stretch">
    <div class="d-none d-sm-block col-2 navbar-dark bg-dark" style="min-height:95vh;">
      <?= W::company_menu() ?>
    </div>
    <div id="companyModel" class="col-12 col-sm-10 p-3">
      <div class="glass rounded shadow p-3">
        <div class="d-flex justify-content-between m-0">
          <h1><i class="<?= W::fa('companies') ?>"></i> <?= _t('companies') ?></h1>
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
          <div id="companyTable" class="col-sm-5">
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
                    text: item.columns()[name].value"
                    class="cursor-pointer"></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-sm-7">
            <div data-bind="with: newItem" id="companyForm"
              class="form-wrapper bg-dark text-light rounded p-3 shadow sticky-top">
              <h2 data-bind="visible: 'new'==collection.state()" class="pb-1">Crear Nueva Empresa</h2>
              <h2 data-bind="visible: 'edit'==collection.state()" class="pb-1">Modificar Empresa</h2>
              <!-- FORMULARIO -->
              <form data-bind="submit: Collection.dataSave" action="#" method="post">
                <div data-bind="foreach: {data: column_names, as: 'cn'}">
                  <div data-bind="with: $parent.columns()[cn]" class="row mb-md-3 g-2">
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
                      <!--div data-bind="text: ko.toJSON(options())">OPTION</div-->
                    </div>
                  </div>
                </div>
                <div class="text-end mt-3">
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

<script>
(function() {

  const COMPANY_COLUMNS  = {
    id:{type: 'hidden'},
    name:{required:1},
    vat:{},
    street:{hide_col:true},
    street2:{hide_col:true},
    zip:{hide_col:true},
    city:{hide_col:true},
    state:{hide_col:true},
    country_code:{hide_col:true},
    //factor:{type:'number',required:1, step:"any"},
    //abbr:{}
  }

  const COMPANY_MESSAGES = {
    create: 'Nueva Empresa creada correctamente',
    update: 'Empresa modificada correctamente',
    delete: 'Empresa eliminada correctamente',
    are_you_sure: '¿Quieres continuar para eliminar esta Empresa?',
  }

  var collection = new Collection('/api/v1/companies',{
    columns: COMPANY_COLUMNS,
    messages: COMPANY_MESSAGES,
  });
  $(function() {
    ko.applyBindings(collection, document.getElementById('companyModel'))
  })
}).call(this)
</script>