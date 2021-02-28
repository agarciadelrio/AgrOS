import ko from "knockout"

class Field {
  item
  key
  value
  type
  options
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
  tdClass() {
    //{'text-end': item.columns()[name].options()}
    return this.options()['td']||null
  }
}

class Item {
  id
  collection
  column_names
  columns
  fields
  constructor(collection, values:any={}) {
    this.id = values.id || null
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
    Collection.self.newItem(this)
    Collection.self.state('edit')
  }
}

export class Collection {
  static self = null
  static url: string = ''
  columns
  messages
  column_names
  table_columns
  state
  newItem
  selectedItem
  items
  constructor(url, options) {
    Collection.url = url
    Collection.self = this
    this.columns = options.columns
    this.messages = options.messages
    this.column_names = ko.observableArray(Object.keys(this.columns))
    console.log('CREARE', Object.keys(this.columns))
    console.log('FILTER', Object.keys(this.columns).filter(c => {
      console.log('C',!this.columns[c].hide_col)
      return true
    } ))
    this.table_columns = ko.observableArray(Object.keys(this.columns).filter(c => !this.columns[c].hide_col ))
    this.state = ko.observable('new')
    this.newItem = ko.observable(new Item(this, {
      id: null,
      name: '',
      symbol: '',
      abbr: '',
      factor:1,
    }))
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
    fetch(Collection.url)
    .then(response => response.json())
    .then(data => {
      this.items(data.items.map(u => {
        return ko.observable(new Item(Collection.self, u))
      }))
    })
    .catch(error => {
      console.log('error', error)
      Collection.showAlert(`ERROR: ${error}`, 'danger')
    })
  }
  static dataSave() {
    Collection.save()
    return false
  }
  static showAlert(msg, type='success') {
    var $a = $('#mainAlert')
    console.log('showAlert', msg, $a)
    $a.removeClass('alert-success alert-danger').addClass('alert-'+type)
    $a.find('.msg:first').html(msg)
    $a.fadeOut(() => {$a.fadeIn()})
    //var alertNode = document.querySelector('.alert')
    //var alert = bootstrap.Alert.getInstance(alertNode)
    //alert.close()
  }
  static closeAlert() {
    var $a = $('#mainAlert')
    $a.fadeOut()
  }
  static save() {
    var self = Collection.self
    var items = self.items
    var data = {}
    self.newItem().fields().forEach(i => {
      data[i.key()] = i.value()
    })
    var url = Collection.url
    var options = {
      method: 'POST',
      body: JSON.stringify(data)
    }
    if(self.state()=='edit'&&self.newItem().columns().id.value()) {
      url = `${url}/${self.newItem().columns().id.value()}`
    }
    fetch(url,options)
    .then(response => response.json())
    .then(data => {
      if(self.state()=='new') {
        items.push(ko.observable(new Item(self, data.item)))
        self.setNew()
        Collection.showAlert(self.messages.create)
      } else {
        Collection.showAlert(self.messages.update)
      }
    })
    .catch(error => {
      console.log('ERROR', error)
      Collection.showAlert(`ERROR: ${error}`, 'danger')
    })
    return false
  }
  static deleteMe() {
    var self = Collection.self
    if(confirm(self.messages.are_you_sure)) {
      var id = self.newItem().columns().id.value()
      if(self.state()=='edit' && id) {
        console.log('DELETE ME', id)
        var url = `${Collection.url}/${id}/delete`
        var options = {
          method: 'POST',
          body: JSON.stringify({id:id,action:'delete'})
        }
        console.log('ITEMS POST', self.items())
        fetch(url,options)
        .then(response => response.json())
        .then(data => {
          Collection.showAlert(self.messages.delete)
          for(var _i in self.items()) {
            var i = self.items()[_i]
            if(i().id==id) {
              self.items.remove(i)
              self.setNew()
              break
            }
          }
        })
        .catch(error => {
          console.log('ERROR', error)
          Collection.showAlert(`ERROR: ${error}`, 'danger')
        })
      }
    }
    return false
  }
}