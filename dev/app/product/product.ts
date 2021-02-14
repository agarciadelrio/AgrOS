import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface ProductOptions {
  id: number
  name: string
}

export class Product {
  static app:App
  static api_url = '/api/v1/products'

  static someSelectedProduct = ko.pureComputed(() => {
    for(let _i in Product.app.products()) {
      const item:Product = Product.app.products()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedProducts = ko.pureComputed(() => {
    return Product.app.products().filter((item:Product) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Product CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Product.app.products()) {
      const item:Product = Product.app.products()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Product CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        product_ids: Product.selectedProducts().map((product) => product.id)
      }
      console.log('Product IDS', body)
      // TODO: post product_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  selected: ko.Observable<boolean>

  constructor(options: ProductOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
  }

  static newProduct(app:App,e:MouseEvent) {
    console.log('NEW Product CLICK',app,e)
    const new_product = new Product({id:0, name:'...'})
    app.selectedProduct(new_product)
  }

  static loadData(products:ko.ObservableArray<Product>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Product.app.api_token(),
      }
    }
    fetch(Product.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      products([])
      data.products.forEach((product:ProductOptions) => {
        products.push(new Product(product))
      })
    })
  }

  select(this:App,self:Product,e:MouseEvent) {
    this.selectedProduct(null)
    const url = `${Product.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Product.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Product DATA', data)
      self.id = data.product.id
      self.name(data.product.name)
      this.selectedProduct(self)
    })
  }

  cancel() {
    console.log('Product CANCEL CLICK', this)
  }

  save(self:Product, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Product SAVE CLICK',e, this)
    const url = `${Product.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Product.app.api_token(),
      },
      body: data
    }
    console.log('Product POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Product DATA POST', data)
      const modalElement = document.getElementById('productModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Product.app.products.push(new Product(data.product))
      }
      bsModal.hide()
    })
  }

}