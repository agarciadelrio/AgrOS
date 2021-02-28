<script>
class Field {
  constructor(item,key,value) {
    this.item = item
    this.key = ko.observable(key)
    this.value = ko.observable(value)
    this.type = ko.pureComputed(() => {
      return this.item.collection.columns[this.key()].type || 'text'
    })
    this.options = ko.computed(() => {
      return this.item.collection.columns[this.key()]||{}
    })
  }
}
class Item {
  constructor(collection, values={}) {
    this.collection = collection
    this.column_names = this.collection.column_names
    this.columns = ko.observable({})
    this.fields = ko.observableArray([])
    this.column_names().forEach(column_name => {
      var field = new Field(this, column_name, values[column_name]||null)
      this.columns()[column_name] = field
      this.fields.push(field)
    })
  }
  select() {
    console.log('CLICK SELECT', this)
    Collection.self.newItem(this)
    Collection.self.state('edit')
  }
}
class Collection {
  static self = null
  constructor(columns) {
    Collection.self = this
    this.columns = columns
    this.column_names = ko.observableArray(Object.keys(columns))
    this.state = ko.observable('new')
    this.newItem = ko.observable(new Item(this, {
      id: null,
      name: '',
      symbol: '',
      abbr: '',
      factor:1,
    }))
    console.log('newItem', ko.toJS(this.newItem()))
    this.selectedItem = ko.observable({})
    this.items = ko.observableArray([])
    this.load()
  }
  setNew() {
    this.newItem(new Item(this, {
      id: null,
      name: '',
      symbol: '',
      abbr: '',
      factor:1,
    }))
    this.state('new')
  }
  load() {
    fetch('/api/v1/uoms')
    .then(response => response.json())
    .then(data => {
      console.log('data', data)
      //console.log(data.uoms.map(u => {
      //  return ko.observable(new Item(Collection.self, u))
      //}))
      this.items(data.uoms.map(u => {
        return ko.observable(new Item(Collection.self, u))
      }))
    })
    .catch(error => {
      console.log('error', error)
    })
  }
  static dataSave() {
    console.log('DATA SAVE')
    Collection.save()
    return false
  }
  static save() {
    var self = Collection.self
    var items = self.items
    console.log('SAVE', self.newItem().fields().map(i => i.value()))
    var data = {}
    self.newItem().fields().forEach(i => {
      data[i.key()] = i.value()
    })
    console.log('items', data)
    var url = '/api/v1/uoms'
    var options = {
      method: 'POST',
      body: JSON.stringify(data)
    }
    console.log('STATE', self.state(), self.newItem().columns().id.value())
    if(self.state()=='edit'&&self.newItem().columns().id.value()) {
      url = '/api/v1/uoms/' + self.newItem().columns().id.value()
    }
    fetch(url,options)
    .then(response => response.json())
    .then(data => {
      console.log('DATA', data)
      if(self.state()=='new') {
        items.push(ko.observable(new Item(self, data.uom)))
      }
    })
    .catch(error => {
      console.log('ERROR', error)
    })
    return false
  }
  static deleteMe() {
    return false
  }
}
</script>
<script>
(function() {

  const UOM_COLUMNS  = {
    id:{type: 'hidden'},
    name:{required:1},
    factor:{type:'number',required:1, step:"any"},
    symbol:{required:1},
    abbr:{}
  }

  console.log('UOM', UOM_COLUMNS)
  var collection = new Collection(UOM_COLUMNS);
  $(function() {
    ko.applyBindings(collection, document.getElementById('uomModel'))
    console.log('collection',ko.toJS(collection))
  })
}).call(this)
</script>
<section class="container-fluid p-0">
  <div class="d-flex align-items-stretch">
    <div class="d-none d-sm-block col-2 navbar-dark bg-dark" style="min-height:95vh;">
      <?= W::category_menu() ?>
    </div>
    <div id="uomModel" class="col-12 col-sm-10 p-3">
      <div class="glass rounded shadow p-3">
        <div class="d-flex justify-content-between">
          <h1><i class="<?= W::fa('uoms') ?>"></i> <?= _t('uoms') ?></h1>
          <span data-bind="visible: 'edit'==state()">
            <button data-bind="click: setNew" class="btn btn-success"><i class="fa fa-plus"></i> NUEVA</button>
          </span>
        </div>
        <hr/>
        <div class="row">
          <div id="uomTable" class="col-6">
            <!-- TABLA -->
            <table class="table table-sm table-striped table-hover">
              <thead>
                <tr data-bind="foreach: column_names">
                  <th data-bind="text: $data"></th>
                </tr>
              </thead>
              <tbody data-bind="foreach: {data: items, as: 'item'} ">
                <tr data-bind="foreach: {data: column_names, as: 'name'}">
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
              class="form-wrapper bg-dark text-light rounded p-3 shadow">
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