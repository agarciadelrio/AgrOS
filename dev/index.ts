import $ from 'jquery'
window.$ = $
window.jQuery = $
import select2 from 'select2/dist/js/select2.js'
select2(jQuery)
window.select2 = select2
import ko, { options } from 'knockout'
import * as bootstrap from 'bootstrap'
import { DataTable } from './core/data-table'
import { Task } from './core/task'
import { JQueryEventHandlerBase } from 'select2'
import { Collection } from './core/collection'
import { Calendar } from './core/calendar/calendar'
import { App as App2 } from './core/app'

declare global {
  interface Window {
    ko: any
    $: JQueryStatic
    jQuery: JQueryStatic
    bootstrap: typeof bootstrap
    select2: typeof select2
    Task: typeof Task
    Collection: typeof Collection
    App: typeof App
    App2: typeof App2
  }
  interface SelectOptions {
    id: number|string
    text: string
  }
}

ko.bindingHandlers.select2Options = {
  update: (element, valueAccessor, allBindingsAccessor, viewModel) => {
    var allBindings = allBindingsAccessor();

    $(element).select2("destroy");
    // see https://github.com/select2/select2/issues/2830
    $(element).html("<option></option>");

    // change selection when options change
    $(element).select2({
      "data": ko.utils.unwrapObservable(valueAccessor())
    });
  }
}

ko.bindingHandlers.select2 = {
  init: (element, valueAccessor, allBindingsAccessor, viewModel) => {
    $(element).select2({
      allowClear:true,
      //multiple:true,
      //selectOnClose:true,
      //closeOnSelect:false,
      width: '100%',
    });
    $('body').on('click','span.select2-selection__choice__display', (e:Event) => {
      e.stopPropagation()
      e.preventDefault()
      e.stopImmediatePropagation()
      $(element).select2()
      console.log('EVENTO CLICK EN:', e)
    })
  }
};

ko.extenders.trackChange = function (target, track) {
  if (track) {
    console.log('TRACK', target(), track);
    //target.isDirty = ko.observable(false);
    target.originalValue = target();
    target.subscribe(function (newValue) {

    })
  }
  return target;
}

ko.extenders.required = function(target, overrideMessage) {
  //add some sub-observables to our observable
  target.hasError = ko.observable();
  target.validationMessage = ko.observable();

  //define a function to do validation
  function validate(newValue) {
    target.hasError(newValue ? false : true);
    target.validationMessage(newValue ? "" : overrideMessage || "This field is required");
  }

  //initial validation
  validate(target());

  //validate whenever the value changes
  target.subscribe(validate);

  //return the original observable
  return target;
};

function todayDate() {
  let d = new Date()
  d.setHours(0,0,0,0)
  return d
}

export class App {
  name: ko.Observable<string>
  calendar: ko.Observable<Calendar>
  static today = todayDate()
  constructor() {
    this.name = ko.observable('HELLO')
    this.calendar = ko.observable(new Calendar({
      init_month: 3,
      init_year: 2021,
      months_counter: 3,
      name: 'Mi calendario'
    }))
  }
}

window.App = App
window.App2 = App2
window.bootstrap = bootstrap
window.ko = ko
window.Task = Task
window.Collection = Collection

console.log('AgrOS')
const api_url = '/api/v1'

$(function(){
  const $tbls = [
    $('#companyFarmTable'),
    $('#userTaskTable'),
  ]
  $tbls.forEach($tbl => {
    if ($tbl.length) {
      console.log('DATA', DataTable.label, $tbl)
      const dt = new DataTable(api_url, $tbl)
      ko.applyBindings(dt, $tbl[0])
    }
  })
})