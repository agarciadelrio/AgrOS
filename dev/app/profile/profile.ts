import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface ProfileOptions {
  id: number
  name: string
}

export class Profile {
  static app:App
  static api_url = '/api/v1/profiles'

  static someSelectedProfile = ko.pureComputed(() => {
    for(let _i in Profile.app.profiles()) {
      const item:Profile = Profile.app.profiles()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedProfiles = ko.pureComputed(() => {
    return Profile.app.profiles().filter((item:Profile) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Profile CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Profile.app.profiles()) {
      const item:Profile = Profile.app.profiles()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Profile CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        profile_ids: Profile.selectedProfiles().map((profile) => profile.id)
      }
      console.log('Profile IDS', body)
      // TODO: post profile_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  selected: ko.Observable<boolean>

  constructor(options: ProfileOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
  }

  static newProfile(app:App,e:MouseEvent) {
    console.log('NEW Profile CLICK',app,e)
    const new_profile = new Profile({id:0, name:'...'})
    app.selectedProfile(new_profile)
  }

  static loadData(profiles:ko.ObservableArray<Profile>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Profile.app.api_token(),
      }
    }
    fetch(Profile.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      profiles([])
      data.profiles.forEach((profile:ProfileOptions) => {
        profiles.push(new Profile(profile))
      })
    })
  }

  select(this:App,self:Profile,e:MouseEvent) {
    this.selectedProfile(null)
    const url = `${Profile.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Profile.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Profile DATA', data)
      self.id = data.profile.id
      self.name(data.profile.name)
      this.selectedProfile(self)
    })
  }

  cancel() {
    console.log('Profile CANCEL CLICK', this)
  }

  save(self:Profile, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Profile SAVE CLICK',e, this)
    const url = `${Profile.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Profile.app.api_token(),
      },
      body: data
    }
    console.log('Profile POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Profile DATA POST', data)
      const modalElement = document.getElementById('profileModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Profile.app.profiles.push(new Profile(data.profile))
      }
      bsModal.hide()
    })
  }

}