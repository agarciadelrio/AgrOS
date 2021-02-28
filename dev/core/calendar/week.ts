import ko from 'knockout'
import { Day, DayOptions } from './day'

export interface WeekOptions {
  nweek:number
  month:number
  year:number
}

export class Week {
  nweek: ko.Observable<number>
  month: ko.Observable<number>
  year: ko.Observable<number>
  days: ko.MaybeObservableArray<Day>

  constructor(options: WeekOptions) {
    this.nweek = ko.observable(options.nweek)
    this.month = ko.observable(options.month)
    this.year = ko.observable(options.year)
  }

}