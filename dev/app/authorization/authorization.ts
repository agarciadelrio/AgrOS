import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface AuthorizationOptions {
  id: number
  name: string
}

export class Authorization {
  static app:App
  static api_url = '/api/v1/authorizations'

  static someSelectedAuthorization = ko.pureComputed(() => {
    for(let _i in Authorization.app.authorizations()) {
      const item:Authorization = Authorization.app.authorizations()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedAuthorizations = ko.pureComputed(() => {
    return Authorization.app.authorizations().filter((item:Authorization) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Authorization CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Authorization.app.authorizations()) {
      const item:Authorization = Authorization.app.authorizations()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Authorization CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        authorization_ids: Authorization.selectedAuthorizations().map((authorization) => authorization.id)
      }
      console.log('Authorization IDS', body)
      // TODO: post authorization_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  selected: ko.Observable<boolean>

  constructor(options: AuthorizationOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
  }

  static newAuthorization(app:App,e:MouseEvent) {
    console.log('NEW Authorization CLICK',app,e)
    const new_authorization = new Authorization({id:0, name:'...'})
    app.selectedAuthorization(new_authorization)
  }

  static loadData(authorizations:ko.ObservableArray<Authorization>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Authorization.app.api_token(),
      }
    }
    fetch(Authorization.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      authorizations([])
      console.log('DATA', data)
      data.authorizations.forEach((authorization:AuthorizationOptions) => {
        authorizations.push(new Authorization(authorization))
      })
    })
  }

  select(this:App,self:Authorization,e:MouseEvent) {
    this.selectedAuthorization(null)
    const url = `${Authorization.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Authorization.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Authorization DATA', data)
      self.id = data.authorization.id
      self.name(data.authorization.name)
      this.selectedAuthorization(self)
    })
  }

  cancel() {
    console.log('Authorization CANCEL CLICK', this)
  }

  save(self:Authorization, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Authorization SAVE CLICK',e, this)
    const url = `${Authorization.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Authorization.app.api_token(),
      },
      body: data
    }
    console.log('Authorization POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Authorization DATA POST', data)
      const modalElement = document.getElementById('authorizationModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Authorization.app.authorizations.push(new Authorization(data.authorization))
      }
      bsModal.hide()
    })
  }

}