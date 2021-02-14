import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface TaxonomyOptions {
  id: number
  name: string
}

export class Taxonomy {
  static app:App
  static api_url = '/api/v1/taxonomies'

  static someSelectedTaxonomy = ko.pureComputed(() => {
    for(let _i in Taxonomy.app.taxonomies()) {
      const item:Taxonomy = Taxonomy.app.taxonomies()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedTaxonomys = ko.pureComputed(() => {
    return Taxonomy.app.taxonomies().filter((item:Taxonomy) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Taxonomy CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Taxonomy.app.taxonomies()) {
      const item:Taxonomy = Taxonomy.app.taxonomies()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Taxonomy CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        taxonomy_ids: Taxonomy.selectedTaxonomys().map((taxonomy) => taxonomy.id)
      }
      console.log('Taxonomy IDS', body)
      // TODO: post taxonomy_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  selected: ko.Observable<boolean>

  constructor(options: TaxonomyOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
  }

  static newTaxonomy(app:App,e:MouseEvent) {
    console.log('NEW Taxonomy CLICK',app,e)
    const new_taxonomy = new Taxonomy({id:0, name:'...'})
    app.selectedTaxonomy(new_taxonomy)
  }

  static loadData(taxonomies:ko.ObservableArray<Taxonomy>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Taxonomy.app.api_token(),
      }
    }
    fetch(Taxonomy.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      taxonomies([])
      data.taxonomies.forEach((taxonomy:TaxonomyOptions) => {
        taxonomies.push(new Taxonomy(taxonomy))
      })
    })
  }

  select(this:App,self:Taxonomy,e:MouseEvent) {
    this.selectedTaxonomy(null)
    const url = `${Taxonomy.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Taxonomy.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Taxonomy DATA', data)
      self.id = data.taxonomy.id
      self.name(data.taxonomy.name)
      this.selectedTaxonomy(self)
    })
  }

  cancel() {
    console.log('Taxonomy CANCEL CLICK', this)
  }

  save(self:Taxonomy, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Taxonomy SAVE CLICK',e, this)
    const url = `${Taxonomy.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Taxonomy.app.api_token(),
      },
      body: data
    }
    console.log('Taxonomy POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Taxonomy DATA POST', data)
      const modalElement = document.getElementById('taxonomyModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Taxonomy.app.taxonomies.push(new Taxonomy(data.taxonomy))
      }
      bsModal.hide()
    })
  }

}