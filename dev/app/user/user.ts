import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface UserOptions {
  id: number
  name: string
  email: string
  password?: string
}

export class User {
  static app:App
  static api_url = '/api/v1/users'
  id: number
  name: ko.Observable<string>
  email: ko.Observable<string>
  password: ko.Observable<string>
  selected: ko.Observable<boolean>

  static someSelectedUser = ko.pureComputed(() => {
    for(let _i in User.app.users()) {
      const item:User = User.app.users()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedUsers = ko.pureComputed(() => {
    return User.app.users().filter((item:User) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('USER CLICK DESELCT ALL',e, e.altKey)
    for(let _i in User.app.users()) {
      const item:User = User.app.users()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('USER CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        user_ids: User.selectedUsers().map((user) => user.id)
      }
      console.log('USER IDS', body)
      // TODO: post user_ids for delete to the PHP-API
    }
  }

  constructor(options: UserOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
    this.email = ko.observable(options.email)
    this.password = ko.observable(options.password || '')
  }

  static newUser(app:App,e:MouseEvent) {
    console.log('NEW USER CLICK',app,e)
    const new_user = new User({id:0, name:'...', email:'@'})
    app.selectedUser(new_user)
  }

  static loadData(users:ko.ObservableArray<User>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + User.app.api_token(),
      }
    }
    fetch(User.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('DATA', data)
      users([])
      data.users.forEach((user:UserOptions) => {
        users.push(new User(user))
      })
    })
  }

  select(this:App,self:User,e:MouseEvent) {
    this.selectedUser(null)
    const url = `${User.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + User.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      //console.log('DATA', data)
      self.id = data.user.id
      self.name(data.user.name)
      self.email(data.user.email)
      this.selectedUser(self)
    })
  }

  cancel() {
    console.log('CANCEL CLICK', this)
  }

  save(self:User, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('SAVE CLICK',e, this)
    const url = `${User.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + User.app.api_token(),
      },
      body: data
    }
    console.log('POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('DATA POST', data)
      const modalElement = document.getElementById('userModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        User.app.users.push(new User(data.user))
      }
      bsModal.hide()
    })
  }
}