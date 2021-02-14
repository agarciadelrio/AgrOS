import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface ParcelOptions {
  id: number
  name: string
  active: any
  farm_id: any
}

export class Parcel {
  static app:App
  static api_url = '/api/v1/parcels'

  static someSelectedParcel = ko.pureComputed(() => {
    for(let _i in Parcel.app.parcels()) {
      const item:Parcel = Parcel.app.parcels()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedParcels = ko.pureComputed(() => {
    return Parcel.app.parcels().filter((item:Parcel) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Parcel CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Parcel.app.parcels()) {
      const item:Parcel = Parcel.app.parcels()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Parcel CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        parcel_ids: Parcel.selectedParcels().map((parcel) => parcel.id)
      }
      console.log('Parcel IDS', body)
      // TODO: post parcel_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  active: ko.Observable<any>
  farm_id: ko.Observable<any>

  selected: ko.Observable<boolean>

  constructor(options: ParcelOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
    this.active = ko.observable(options.active)
    this.farm_id = ko.observable(options.farm_id)
  }

  static newParcel(app:App,e:MouseEvent) {
    console.log('NEW Parcel CLICK',app,e)
    const new_parcel = new Parcel({
      id:0,
      name:'',
      active:'',
      farm_id:'',
    })
    app.selectedParcel(new_parcel)
  }

  static loadData(parcels:ko.ObservableArray<Parcel>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Parcel.app.api_token(),
      }
    }
    fetch(Parcel.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      parcels([])
      data.parcels.forEach((parcel:ParcelOptions) => {
        parcels.push(new Parcel(parcel))
      })
    })
  }

  select(this:App,self:Parcel,e:MouseEvent) {
    this.selectedParcel(null)
    const url = `${Parcel.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Parcel.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Parcel DATA', data)
      self.id = data.parcel.id
      self.name(data.parcel.name)
      self.active(data.parcel.active)
      self.farm_id(data.parcel.farm_id)
      this.selectedParcel(self)
    })
  }

  cancel() {
    console.log('Parcel CANCEL CLICK', this)
  }

  save(self:Parcel, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Parcel SAVE CLICK',e, this)
    const url = `${Parcel.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Parcel.app.api_token(),
      },
      body: data
    }
    console.log('Parcel POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Parcel DATA POST', data)
      const modalElement = document.getElementById('parcelModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Parcel.app.parcels.push(new Parcel(data.parcel))
      }
      bsModal.hide()
    })
  }

}