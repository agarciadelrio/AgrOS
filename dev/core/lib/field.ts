import ko from 'knockout'
import { UUID } from './functions'

export class Field {
  _id
  type
  name
  value
  required
  tip
  attr
  constructor(type, name, value, required, tip, attr) {
    this.type = type
    this.name = name
    this._id = UUID() + '-' + name
    this.tip = tip
    this.required = required
    this.value = value
    this.attr = ko.pureComputed(() => {
      let a:any = {id: this._id, required: this.required}
      if(['text','textarea'].includes(this.type) && this.tip) a.maxlength = this.tip
      return {...a, ...attr||{}}
    })
  }
}