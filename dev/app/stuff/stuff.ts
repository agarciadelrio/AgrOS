import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface StuffOptions {
  id: number
  name: string
}

export class Stuff {
  static app:App
  static api_url = '/api/v1/stuffs'

  static someSelectedStuff = ko.pureComputed(() => {
    for(let _i in Stuff.app.stuffs()) {
      const item:Stuff = Stuff.app.stuffs()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedStuffs = ko.pureComputed(() => {
    return Stuff.app.stuffs().filter((item:Stuff) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Stuff CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Stuff.app.stuffs()) {
      const item:Stuff = Stuff.app.stuffs()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Stuff CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        stuff_ids: Stuff.selectedStuffs().map((stuff) => stuff.id)
      }
      console.log('Stuff IDS', body)
      // TODO: post stuff_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  selected: ko.Observable<boolean>

  constructor(options: StuffOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
  }

  static newStuff(app:App,e:MouseEvent) {
    console.log('NEW Stuff CLICK',app,e)
    const new_stuff = new Stuff({id:0, name:'...'})
    app.selectedStuff(new_stuff)
  }

  static loadData(stuffs:ko.ObservableArray<Stuff>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Stuff.app.api_token(),
      }
    }
    fetch(Stuff.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      stuffs([])
      data.stuffs.forEach((stuff:StuffOptions) => {
        stuffs.push(new Stuff(stuff))
      })
    })
  }

  select(this:App,self:Stuff,e:MouseEvent) {
    this.selectedStuff(null)
    const url = `${Stuff.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Stuff.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Stuff DATA', data)
      self.id = data.stuff.id
      self.name(data.stuff.name)
      this.selectedStuff(self)
    })
  }

  cancel() {
    console.log('Stuff CANCEL CLICK', this)
  }

  save(self:Stuff, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Stuff SAVE CLICK',e, this)
    const url = `${Stuff.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Stuff.app.api_token(),
      },
      body: data
    }
    console.log('Stuff POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Stuff DATA POST', data)
      const modalElement = document.getElementById('stuffModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Stuff.app.stuffs.push(new Stuff(data.stuff))
      }
      bsModal.hide()
    })
  }

}