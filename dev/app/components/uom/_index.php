<section class="container-fluid p-0">
  <div class="d-flex align-items-stretch">
    <div class="d-none d-sm-block col-2 navbar-dark bg-dark" style="min-height:95vh;">
      <?= W::category_menu() ?>
    </div>
    <div id="uomModel" class="col-12 col-sm-10 p-3">
      <div class="glass rounded shadow p-3">
        <div class="d-flex justify-content-between m-0">
          <h1><i class="<?= W::fa('uoms') ?>"></i> <?= _t('uoms') ?></h1>
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
          <div id="uomTable" class="col-6">
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
          <div class="col-6">
            <div data-bind="with: newItem" id="uomForm"
              class="form-wrapper bg-dark text-light rounded p-3 shadow sticky-top">
              <h2 data-bind="visible: 'new'==collection.state()" class="pb-1">Crear Nueva UDM</h2>
              <h2 data-bind="visible: 'edit'==collection.state()" class="pb-1">Modificar UDM</h2>
              <!-- FORMULARIO -->
              <form data-bind="submit: Collection.dataSave" action="#" method="post">
                <div data-bind="foreach: {data: column_names, as: 'cn'}">
                  <div data-bind="with: $parent.columns()[cn]" class="row mb-md-3 g-2">
                    <label data-bind="text: cn, visible: 'hidden'!=type()" class="col-sm-3 col-form-label"></label>
                    <div class="col-sm-9">
                      <input data-bind="
                        value: value,
                        attr:{
                          type: type,
                          required: (options().required||null) ? true:false,
                          step: type()=='number' ? options().step||false : false,
                        },
                        "
                        type="text" class="form-control"/>
                      <!--div data-bind="text: ko.toJSON(options())">OPTION</div-->
                    </div>
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

<script>
(function() {

  const UOM_COLUMNS  = {
    id:{type: 'hidden'},
    name:{required:1},
    factor:{type:'number',required:1, step:"any"},
    symbol:{required:1},
    abbr:{}
  }

  const UOM_MESSAGES = {
    create: 'Nueva UDM creada correctamente',
    update: 'UDM modificada correctamente',
    delete: 'UDM eliminada correctamente',
    are_you_sure: '¿Quieres continuar para eliminar esta UDM?',
  }

  var collection = new Collection('/api/v1/uoms',{
    columns: UOM_COLUMNS,
    messages: UOM_MESSAGES,
  });
  $(function() {
    ko.applyBindings(collection, document.getElementById('uomModel'))
  })
}).call(this)
</script>