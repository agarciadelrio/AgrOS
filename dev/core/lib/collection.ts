import ko from 'knockout'
import { App } from '../app'
import { Field } from './field'
import { Record } from './record'

export class Collection {
  app
  records
  fields_format
  columns
  selectedRecord:ko.Observable<Record>
  constructor(app:App, api_end_point, model_name, form_columns, list_columns) {
    this.app = app
    this.records = ko.observableArray([])
    this.fields_format = ko.observableArray([])
    this.columns = ko.observableArray([])
    this.selectedRecord = ko.observable(null)
    this.proc_form_columns(form_columns)
    this.proc_list_column(list_columns)
  }

  proc_form_columns(form_columns) {
    form_columns.forEach(column => {
      let type, name, tip, required, attr
      switch (column[0]) {
        case '#': // # number
          type = 'number'
          name = column.substring(1)
          break
        case '$': // # currency
          type = 'currency'
          name = column.substring(1)
          break
        case '%': // # hidden
          type = 'hidden'
          name = column.substring(1)
          break
        case '"': // " textarea
          type = 'textarea'
          name = column.substring(1)
          break
        case '*': // * password
          type = 'password'
          name = column.substring(1)
          break
        case '/': // / date
          type = 'date'
          name = column.substring(1)
          break
        case ':': // : time
          type = 'time'
          name = column.substring(1)
          break
        case '^': // ^ link
          type = 'link'
          name = column.substring(1)
          break
        case '[': // [ collection
          type = 'collection'
          name = column.substring(1)
          break
        case '+': // + select
          type = 'select'
          name = column.substring(1)
          break
        case '-': // - range
          type = 'range'
          name = column.substring(1)
          break
        case '@': // @ email
          type = 'email'
          name = column.substring(1)
          break
        case '{': // { phone
          type = 'tel'
          name = column.substring(1)
          break
        case '?': // ? checkbox
          type = 'checkbox'
          name = column.substring(1)
          break
        case '(': // ( radio
          type = 'radio'
          name = column.substring(1)
          break
        case '.': // . file
          type = 'file'
          name = column.substring(1)
          break
        default:
          type = 'text'
          name = column
      }
      if(name[name.length - 1]=='!') {
        required = true
        name = name.slice(0, -1)
      } else { required = false }
      [name,tip,attr] = name.split('|')
      eval(`attr = ${attr || '{}'}`)
      this.fields_format.push(new Field(type, name, ko.observable(''), required, tip, attr))
    })
  }

  proc_list_column(list_columns) {
    list_columns.forEach(column => {
      let klass = []
      let name = column
      switch (name[name.length - 1]) {
        case '^':
          klass.push('text-end')
          name = name.slice(0, -1)
          break
        case '=':
          klass.push('text-center')
          name = name.slice(0, -1)
          break
        default:
          klass.push('text-start')
          break
      }
      this.columns.push({
        name: name,
        class: klass.join(' '),
        thTpl: name=='#' ? 'thSelTemplate':'thTemplate',
        tdTpl: name=='#' ? 'tdSelTemplate': (name=='id' ? 'tdIdTemplate' :'tdTemplate'),
      })
    })
  }

  load_records(items) {
    items.forEach(item => {
      let record = new Record(this, item.id, item)
      this.records.push(record)
    })
  }

  setNew() {
    console.log('SET NEW')
    this.app.editing(false)
    this.selectedRecord(null)
    this.fields_format().forEach(field => {
      console.log('Field', field)
      field.value('')
    })
  }

  deleteMe() {
    console.log('COLLECTION DELETE ME', this.selectedRecord().id())
    if(confirm('¿QUIERE CONTINUAR PARA ELIMINAR?')) {
      const url = `${this.app.api_end_point}/${this.app.plural}/${this.selectedRecord().id()}/delete`
      console.log('URL', url)
      const options = {
        method: 'POST',
        body: JSON.stringify({
          id: this.selectedRecord().id(),
          action: 'delete',
        })
      }
      fetch(url,options)
      .then(response => response.json())
      .then(data => {
        this.records.remove(this.selectedRecord())
        this.selectedRecord(null)
      })
      .catch(err => console.error('ERROR ELIMINANDO', err))
    }
  }

}