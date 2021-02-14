import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface PropertyOptions {
  id: number
  name: string
  active: any
  register_code: any
  description: any
  latitude: any
  longitude: any
  altitude: any
}

export class Property {
  static app:App
  static api_url = '/api/v1/properties'

  static someSelectedProperty = ko.pureComputed(() => {
    for(let _i in Property.app.properties()) {
      const item:Property = Property.app.properties()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedPropertys = ko.pureComputed(() => {
    return Property.app.properties().filter((item:Property) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Property CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Property.app.properties()) {
      const item:Property = Property.app.properties()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Property CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        property_ids: Property.selectedPropertys().map((property) => property.id)
      }
      console.log('Property IDS', body)
      // TODO: post property_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  active: ko.Observable<any>
  register_code: ko.Observable<any>
  description: ko.Observable<any>
  latitude: ko.Observable<any>
  longitude: ko.Observable<any>
  altitude: ko.Observable<any>

  selected: ko.Observable<boolean>

  constructor(options: PropertyOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
    this.active = ko.observable(options.active)
    this.register_code = ko.observable(options.register_code)
    this.description = ko.observable(options.description)
    this.latitude = ko.observable(options.latitude)
    this.longitude = ko.observable(options.longitude)
    this.altitude = ko.observable(options.altitude)
  }

  static newProperty(app:App,e:MouseEvent) {
    console.log('NEW Property CLICK',app,e)
    const new_property = new Property({
      id:0,
      name:'',
      active:'',
      register_code:'',
      description:'',
      latitude:'',
      longitude:'',
      altitude:'',
    })
    app.selectedProperty(new_property)
  }

  static loadData(properties:ko.ObservableArray<Property>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Property.app.api_token(),
      }
    }
    fetch(Property.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      properties([])
      data.properties.forEach((property:PropertyOptions) => {
        properties.push(new Property(property))
      })
    })
  }

  select(this:App,self:Property,e:MouseEvent) {
    this.selectedProperty(null)
    const url = `${Property.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Property.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Property DATA', data)
      self.id = data.property.id
      self.name(data.property.name)
      self.active(data.property.active)
      self.register_code(data.property.register_code)
      self.description(data.property.description)
      self.latitude(data.property.latitude)
      self.longitude(data.property.longitude)
      self.altitude(data.property.altitude)
      this.selectedProperty(self)
    })
  }

  cancel() {
    console.log('Property CANCEL CLICK', this)
  }

  save(self:Property, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Property SAVE CLICK',e, this)
    const url = `${Property.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Property.app.api_token(),
      },
      body: data
    }
    console.log('Property POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Property DATA POST', data)
      const modalElement = document.getElementById('propertyModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Property.app.properties.push(new Property(data.property))
      }
      bsModal.hide()
    })
  }

}