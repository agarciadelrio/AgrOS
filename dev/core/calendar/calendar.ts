import ko from "knockout"
import { Month } from "./month"

export interface CalendarOptions {
  name:string
  init_year:number
  init_month:number
  months_counter:number
}

export class Calendar {
  name: ko.Observable<string>
  init_year: ko.Observable<number>
  init_month: ko.Observable<number>
  months_counter: ko.Observable<number>
  months: ko.ObservableArray<Month>
  css: ko.PureComputed<any>

  constructor(options: CalendarOptions) {
    this.name = ko.observable(options.name)
    this.init_year = ko.observable(options.init_year)
    this.init_month = ko.observable(options.init_month - 1)
    this.months_counter = ko.observable(options.months_counter)
    this.months = ko.observableArray([])
    var date = new Date(this.init_year(),this.init_month(),1)
    for(let m=0; m<this.months_counter(); m++) {
      this.months.push(new Month(date))
      date.setMonth(date.getMonth()+1)
    }
    this.css = ko.pureComputed(()=> {
      return {
        horizontal: true
      }
    })
  }
}