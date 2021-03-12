import ko from 'knockout'
import { Field } from './field'

export class Record {
  collection
  id
  fields
  selected
  constructor(collection,id, item) {
    this.collection = collection
    this.id = ko.observable(id||0)
    this.selected = ko.observable(false)
    this.fields = ko.observable({})
    let keys = Object.keys(item)
    this.collection.fields_format().forEach(col => {
      if(keys.includes(col.name)) {
        //console.log('COL', col.name, item[col.name])
        const field = new Field(
          col.type,
          col.name,
          ko.observable(item[col.name]),
          col.required,
          col.tip,
          col.attr
        )
        this.fields()[col.name] = field
      }
    })
  }
  selectRow(record, ev:MouseEvent) {
    console.log('SELECT', this, ev)
    console.log('TAG', (ev.target as HTMLElement).tagName)
    if((ev.target as HTMLElement).tagName=='I') {
      console.log('SELECT ONE')
      record.selected(!record.selected())
      //this.selected(true)
      ev.stopPropagation()
      ev.preventDefault()
    } else {
      this.collection.selectedRecord(this)
      this.collection.app.editing(true)
      const rec = this.collection.records().find(r => r.id() == this.id()) || null
      this.collection.fields_format().forEach(f => {
        if(rec.fields().hasOwnProperty(f.name)) {
          f.value(rec.fields()[f.name].value())
        } else {
          f.value('')
        }
      })
    }
  }
}