import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.css'
import '@fortawesome/fontawesome-free/css/all.css'

import ko from 'knockout'
import { Collection } from './lib/collection'
import { pluralize } from './lib/functions';
import { Record } from './lib/record'
console.log('KO-Collection')

export class App {
  api_end_point
  api_url
  collection
  formTitle
  model_name
  plural
  editing
  total_pages
  total_items
  globalMsg
  page
  constructor(api_end_point, model_name, form_columns, list_columns) {
    this.model_name = model_name
    this.api_end_point = api_end_point
    this.plural = pluralize(model_name)
    this.collection = new Collection(this, api_end_point, model_name, form_columns, list_columns)
    this.api_url = `${this.api_end_point}/${this.plural}`
    this.editing = ko.observable(false)
    this.total_pages = ko.observable(0)
    this.total_items = ko.observable(0)
    this.page = ko.observable(1)
    this.formTitle = ko.pureComputed(() => {
      if (this.collection.selectedRecord()) {
        let name = this.collection.selectedRecord().fields()['name'] ? this.collection.selectedRecord().fields()['name'].value():null
        return `Editar ${this.model_name}: ${name || this.collection.selectedRecord().id()}`
      } else {
        return `Nuevo ${this.model_name}`
      }
    })
    this.globalMsg = ko.observable('')
  }

  loadData() {
    //console.log('LOADING')
    return fetch(this.api_url)
      .then(response => response.json())
      .then(data => {
        console.log('DATA', data)
        this.total_pages(data.total_pages||0)
        this.total_items(data.total||0)
        this.collection.load_records(data.items)
      })
      .catch(err => console.error(`ERROR ${this.model_name}:`, err))
  }

  sendData() {
    let url
    let data = {}
    this.collection.fields_format().forEach(field => {
      data[field.name] = field.value()
    })
    console.log('SEND DATA', data)
    if(this.collection.selectedRecord()) {
      console.log('ID', this.collection.selectedRecord().id())
      let id = this.collection.selectedRecord().id()||0
      url = `${this.api_url}/${id}`
    } else {
      url = this.api_url
    }
    let options = {
      method: 'POST',
      body: JSON.stringify(data)
    }
    console.log('URL', url)
    fetch(url,options)
    .then(response => response.json())
    .then(data => {
      console.log('DATA', data)
      if(this.collection.selectedRecord()) {
        this.globalMsg('Datos modificados correctamente')
        this.collection.fields_format().forEach(field => {
          //data[field.name] = field.value()
          this.collection.selectedRecord().fields()[field.name].value(field.value())
          console.log('ACTUALIZADO', field)
        })
      } else {
        this.globalMsg('Datos creados correctamente')
        // crear nuevo en la lista
        let record = new Record(this.collection, data.item.id, data.item)
        this.collection.records.push(record)
      }
      setTimeout(()=>{this.globalMsg(false)},2000)
    })
    .catch(err => console.error('ERROR SENDING DATA', err))
    return false
  }

  prevPage() {
    if(this.page()>1) {
      this.page(this.page()-1)
      this.api_url = `${this.api_end_point}/${this.plural}?page=${this.page()}`
      this.collection.records([])
      this.loadData()
    }
    console.log('prevPage')
  }

  nextPage() {
    if(this.page()<=this.total_pages()) {
      this.page(this.page()+1)
      this.api_url = `${this.api_end_point}/${this.plural}?page=${this.page()}`
      this.collection.records([])
      this.loadData()
    }
    console.log('nextPage', this.api_url, this.page())
  }
}