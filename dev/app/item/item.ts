import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface ItemOptions {
  id: number
  name: string
}

export class Item {
  static app:App
  static api_url = '/api/v1/items'

  static someSelectedItem = ko.pureComputed(() => {
    for(let _i in Item.app.items()) {
      const item:Item = Item.app.items()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedItems = ko.pureComputed(() => {
    return Item.app.items().filter((item:Item) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Item CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Item.app.items()) {
      const item:Item = Item.app.items()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Item CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        item_ids: Item.selectedItems().map((item) => item.id)
      }
      console.log('Item IDS', body)
      // TODO: post item_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  selected: ko.Observable<boolean>

  constructor(options: ItemOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
  }

  static newItem(app:App,e:MouseEvent) {
    console.log('NEW Item CLICK',app,e)
    const new_item = new Item({id:0, name:'...'})
    app.selectedItem(new_item)
  }

  static loadData(items:ko.ObservableArray<Item>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Item.app.api_token(),
      }
    }
    fetch(Item.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      items([])
      data.items.forEach((item:ItemOptions) => {
        items.push(new Item(item))
      })
    })
  }

  select(this:App,self:Item,e:MouseEvent) {
    this.selectedItem(null)
    const url = `${Item.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Item.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Item DATA', data)
      self.id = data.item.id
      self.name(data.item.name)
      this.selectedItem(self)
    })
  }

  cancel() {
    console.log('Item CANCEL CLICK', this)
  }

  save(self:Item, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Item SAVE CLICK',e, this)
    const url = `${Item.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Item.app.api_token(),
      },
      body: data
    }
    console.log('Item POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Item DATA POST', data)
      const modalElement = document.getElementById('itemModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Item.app.items.push(new Item(data.item))
      }
      bsModal.hide()
    })
  }

}