import ko from "knockout"
import { Day } from "./day";
import { Week, WeekOptions } from './week';

export interface MonthOptions {
  year:number
  month:number
  weeks?:WeekOptions[]
}

export class Month {
  year: ko.Observable<number>
  month: ko.Observable<number>
  weeks: ko.ObservableArray<Week|number>
  name: ko.PureComputed<string>

  static wdays = ko.observableArray([
    {label: 'L'},
    {label: 'M'},
    {label: 'X'},
    {label: 'J'},
    {label: 'V'},
    {label: 'S'},
    {label: 'D'},
  ])

  static months = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];

  constructor(options: MonthOptions|Date) {
    let _weeks = []
    if(options instanceof Date) {
      this.year = ko.observable(options.getFullYear())
      this.month = ko.observable(options.getMonth())
      let first_date = new Date(this.year(), this.month(), 1)
      let last_date = new Date(this.year(), this.month()+1, 0)
      let wfirstday = first_date.getDay()-1
      if(wfirstday<0) wfirstday = 6
      let wlastday = last_date.getDay()-1
      if(wlastday<0) wlastday = 6
      wlastday = 6-wlastday

      first_date.setDate(first_date.getDate()-wfirstday)
      last_date.setDate(last_date.getDate()+wlastday)
      const diffdates = Math.round((last_date.getTime() - first_date.getTime()) / (1000*60*60*24))+1
      for(let w=0; w<diffdates/7; w++) {
        let days = []
        for(let d=0; d<Month.wdays().length; d++) {
          const _d = first_date.getDate()
          days.push(new Day(this, first_date))
          first_date.setDate(_d+1)
        }
        _weeks.push(days)
      }
      console.log('WEEEEKS',_weeks)
    } else {
      this.year = ko.observable(options.year)
      this.month = ko.observable(options.month)
      _weeks = options.weeks.map(week => new Week(week))
    }
    this.weeks = ko.observableArray(_weeks)
    this.name = ko.pureComputed(() => {
      return `${Month.months[this.month()]} ${this.year()}`
    })
  }
}

window['Month'] = Month