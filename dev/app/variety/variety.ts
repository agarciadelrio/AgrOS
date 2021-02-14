import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface VarietyOptions {
  id: number
  name: string
}

export class Variety {
  static app:App
  static api_url = '/api/v1/varieties'

  static someSelectedVariety = ko.pureComputed(() => {
    for(let _i in Variety.app.varieties()) {
      const item:Variety = Variety.app.varieties()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedVarietys = ko.pureComputed(() => {
    return Variety.app.varieties().filter((item:Variety) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Variety CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Variety.app.varieties()) {
      const item:Variety = Variety.app.varieties()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Variety CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        variety_ids: Variety.selectedVarietys().map((variety) => variety.id)
      }
      console.log('Variety IDS', body)
      // TODO: post variety_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  selected: ko.Observable<boolean>

  constructor(options: VarietyOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
  }

  static newVariety(app:App,e:MouseEvent) {
    console.log('NEW Variety CLICK',app,e)
    const new_variety = new Variety({id:0, name:'...'})
    app.selectedVariety(new_variety)
  }

  static loadData(varieties:ko.ObservableArray<Variety>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Variety.app.api_token(),
      }
    }
    fetch(Variety.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      varieties([])
      data.varieties.forEach((variety:VarietyOptions) => {
        varieties.push(new Variety(variety))
      })
    })
  }

  select(this:App,self:Variety,e:MouseEvent) {
    this.selectedVariety(null)
    const url = `${Variety.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Variety.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Variety DATA', data)
      self.id = data.variety.id
      self.name(data.variety.name)
      this.selectedVariety(self)
    })
  }

  cancel() {
    console.log('Variety CANCEL CLICK', this)
  }

  save(self:Variety, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Variety SAVE CLICK',e, this)
    const url = `${Variety.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Variety.app.api_token(),
      },
      body: data
    }
    console.log('Variety POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Variety DATA POST', data)
      const modalElement = document.getElementById('varietyModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Variety.app.varieties.push(new Variety(data.variety))
      }
      bsModal.hide()
    })
  }

}