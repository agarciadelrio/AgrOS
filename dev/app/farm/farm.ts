import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface FarmOptions {
  id: number
  name: string
  active: any
}

export class Farm {
  static app:App
  static api_url = '/api/v1/farms'

  static someSelectedFarm = ko.pureComputed(() => {
    for(let _i in Farm.app.farms()) {
      const item:Farm = Farm.app.farms()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedFarms = ko.pureComputed(() => {
    return Farm.app.farms().filter((item:Farm) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Farm CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Farm.app.farms()) {
      const item:Farm = Farm.app.farms()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Farm CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        farm_ids: Farm.selectedFarms().map((farm) => farm.id)
      }
      console.log('Farm IDS', body)
      // TODO: post farm_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  active: ko.Observable<any>

  selected: ko.Observable<boolean>

  constructor(options: FarmOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
    this.active = ko.observable(options.active)
  }

  static newFarm(app:App,e:MouseEvent) {
    console.log('NEW Farm CLICK',app,e)
    const new_farm = new Farm({
      id:0,
      name:'',
      active:'',
    })
    app.selectedFarm(new_farm)
  }

  static loadData(farms:ko.ObservableArray<Farm>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Farm.app.api_token(),
      }
    }
    fetch(Farm.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      farms([])
      data.farms.forEach((farm:FarmOptions) => {
        farms.push(new Farm(farm))
      })
    })
  }

  select(this:App,self:Farm,e:MouseEvent) {
    this.selectedFarm(null)
    const url = `${Farm.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Farm.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Farm DATA', data)
      self.id = data.farm.id
      self.name(data.farm.name)
      this.selectedFarm(self)
    })
  }

  cancel() {
    console.log('Farm CANCEL CLICK', this)
  }

  save(self:Farm, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Farm SAVE CLICK',e, this)
    const url = `${Farm.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Farm.app.api_token(),
      },
      body: data
    }
    console.log('Farm POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Farm DATA POST', data)
      const modalElement = document.getElementById('farmModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Farm.app.farms.push(new Farm(data.farm))
      }
      bsModal.hide()
    })
  }

}