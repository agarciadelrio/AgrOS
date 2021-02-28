import ko from 'knockout'
import { Day } from './day';

export interface HourOptions {
  day: Day|null
  hour: number
  minute: number
}

export class Hour {
  day: ko.Observable<Day>;
  hour: ko.Observable<number>
  minute: ko.Observable<number>

  constructor(day:Day, options:HourOptions) {
    this.day = ko.observable(day||options.day||null)
    this.hour = ko.observable(options.hour)
    this.minute = ko.observable(options.minute)
  }
}