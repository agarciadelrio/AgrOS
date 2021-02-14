import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface CompanyOptions {
  id: number
  name: string
}

export class Company {
  static app:App
  static api_url = '/api/v1/companies'

  static someSelectedCompany = ko.pureComputed(() => {
    for(let _i in Company.app.companies()) {
      const item:Company = Company.app.companies()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedCompanies = ko.pureComputed(() => {
    return Company.app.companies().filter((item:Company) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Company CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Company.app.companies()) {
      const item:Company = Company.app.companies()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(!confirm('Are you sure you want to delete these items?')) {
      return false;
    }

    const body = {
      action: 'delete',
      company_ids: Company.selectedCompanies().map((company) => company.id)
    }

    const url = `${Company.api_url}/delete`
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Company.app.api_token(),
      },
      body: JSON.stringify(body)
    }

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      if(data.msg=='DELETE') {
        Company.app.companies.remove( item => data.ids.includes(item.id) )
      }
    })
  }

  id: number
  name: ko.Observable<string>

  selected: ko.Observable<boolean>

  constructor(options: CompanyOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
  }

  static newCompany(app:App,e:MouseEvent) {
    console.log('NEW Company CLICK',app,e)
    const new_company = new Company({
      id:0,
      name:'',
    })
    app.selectedCompany(new_company)
  }

  static loadData(companies:ko.ObservableArray<Company>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Company.app.api_token(),
      }
    }
    fetch(Company.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      companies([])
      data.companies.forEach((company:CompanyOptions) => {
        companies.push(new Company(company))
      })
    })
  }

  select(this:App,self:Company,e:MouseEvent) {
    this.selectedCompany(null)
    const url = `${Company.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Company.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Company DATA', data)
      self.id = data.company.id
      self.name(data.company.name)
      this.selectedCompany(self)
    })
  }

  cancel() {
    console.log('Company CANCEL CLICK', this)
  }

  save(self:Company, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Company SAVE CLICK',e, this)
    const url = `${Company.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Company.app.api_token(),
      },
      body: data
    }
    console.log('Company POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Company DATA POST', data)
      const modalElement = document.getElementById('companyModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Company.app.companies.push(new Company(data.company))
      }
      bsModal.hide()
    })
  }

}