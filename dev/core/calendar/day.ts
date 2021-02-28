import ko from 'knockout'
import { App } from '../../index'
import { Month } from './month'

export interface DayOptions {
  day: number
  month: number
  year: number
}

export class Day {
  month_parent: ko.Observable<Month>
  day: ko.Observable<number>
  month: ko.Observable<number>
  year: ko.Observable<number>
  date: ko.Observable<Date>
  css: ko.PureComputed<any>

  constructor(month_parent:Month, options: DayOptions|Date) {
    this.month_parent = ko.observable(month_parent)
    if(options instanceof Date) {
      this.day = ko.observable(options.getDate())
      this.month = ko.observable(options.getMonth())
      this.year = ko.observable(options.getFullYear())
      this.date = ko.observable(new Date(options.valueOf()))
    } else {
      this.day = ko.observable(options.day)
      this.month = ko.observable(options.month)
      this.year = ko.observable(options.year)
    }
    this.css = ko.pureComputed(()=> {
      return {
        today: this.date().getTime()==App.today.getTime(),
        weekend: [0,6].includes(this.date().getDay()),
        outdate: this.month_parent().month() != this.month()
      }
    })
  }
}