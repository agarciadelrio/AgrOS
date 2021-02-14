import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface HandlerOptions {
  id: number
  name: string
  code: any
  vat: any
  description: any
}

export class Handler {
  static app:App
  static api_url = '/api/v1/handlers'

  static someSelectedHandler = ko.pureComputed(() => {
    for(let _i in Handler.app.handlers()) {
      const item:Handler = Handler.app.handlers()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedHandlers = ko.pureComputed(() => {
    return Handler.app.handlers().filter((item:Handler) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Handler CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Handler.app.handlers()) {
      const item:Handler = Handler.app.handlers()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Handler CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        handler_ids: Handler.selectedHandlers().map((handler) => handler.id)
      }
      console.log('Handler IDS', body)
      // TODO: post handler_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  code: ko.Observable<any>
  vat: ko.Observable<any>
  description: ko.Observable<any>

  selected: ko.Observable<boolean>

  constructor(options: HandlerOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
    this.code = ko.observable(options.code)
    this.vat = ko.observable(options.vat)
    this.description = ko.observable(options.description)
  }

  static newHandler(app:App,e:MouseEvent) {
    console.log('NEW Handler CLICK',app,e)
    const new_handler = new Handler({
      id:0,
      name:'',
      code:'',
      vat:'',
      description:'',
    })
    app.selectedHandler(new_handler)
  }

  static loadData(handlers:ko.ObservableArray<Handler>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Handler.app.api_token(),
      }
    }
    fetch(Handler.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      handlers([])
      data.handlers.forEach((handler:HandlerOptions) => {
        handlers.push(new Handler(handler))
      })
    })
  }

  select(this:App,self:Handler,e:MouseEvent) {
    this.selectedHandler(null)
    const url = `${Handler.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Handler.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Handler DATA', data)
      self.id = data.handler.id
      self.name(data.handler.name)
      self.code(data.handler.code)
      self.vat(data.handler.vat)
      self.description(data.handler.description)
      this.selectedHandler(self)
    })
  }

  cancel() {
    console.log('Handler CANCEL CLICK', this)
  }

  save(self:Handler, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Handler SAVE CLICK',e, this)
    const url = `${Handler.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Handler.app.api_token(),
      },
      body: data
    }
    console.log('Handler POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Handler DATA POST', data)
      const modalElement = document.getElementById('handlerModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Handler.app.handlers.push(new Handler(data.handler))
      }
      bsModal.hide()
    })
  }

}