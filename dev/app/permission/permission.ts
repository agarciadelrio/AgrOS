import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface PermissionOptions {
  id: number
  name: string
}

export class Permission {
  static app:App
  static api_url = '/api/v1/permissions'

  static someSelectedPermission = ko.pureComputed(() => {
    for(let _i in Permission.app.permissions()) {
      const item:Permission = Permission.app.permissions()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedPermissions = ko.pureComputed(() => {
    return Permission.app.permissions().filter((item:Permission) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Permission CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Permission.app.permissions()) {
      const item:Permission = Permission.app.permissions()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Permission CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        permission_ids: Permission.selectedPermissions().map((permission) => permission.id)
      }
      console.log('Permission IDS', body)
      // TODO: post permission_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  selected: ko.Observable<boolean>

  constructor(options: PermissionOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
  }

  static newPermission(app:App,e:MouseEvent) {
    console.log('NEW Permission CLICK',app,e)
    const new_permission = new Permission({id:0, name:'...'})
    app.selectedPermission(new_permission)
  }

  static loadData(permissions:ko.ObservableArray<Permission>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Permission.app.api_token(),
      }
    }
    fetch(Permission.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      permissions([])
      data.permissions.forEach((permission:PermissionOptions) => {
        permissions.push(new Permission(permission))
      })
    })
  }

  select(this:App,self:Permission,e:MouseEvent) {
    this.selectedPermission(null)
    const url = `${Permission.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Permission.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Permission DATA', data)
      self.id = data.permission.id
      self.name(data.permission.name)
      this.selectedPermission(self)
    })
  }

  cancel() {
    console.log('Permission CANCEL CLICK', this)
  }

  save(self:Permission, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Permission SAVE CLICK',e, this)
    const url = `${Permission.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Permission.app.api_token(),
      },
      body: data
    }
    console.log('Permission POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Permission DATA POST', data)
      const modalElement = document.getElementById('permissionModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Permission.app.permissions.push(new Permission(data.permission))
      }
      bsModal.hide()
    })
  }

}