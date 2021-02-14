import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface MemberOptions {
  id: number
  name: string
}

export class Member {
  static app:App
  static api_url = '/api/v1/members'

  static someSelectedMember = ko.pureComputed(() => {
    for(let _i in Member.app.members()) {
      const item:Member = Member.app.members()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedMembers = ko.pureComputed(() => {
    return Member.app.members().filter((item:Member) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Member CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Member.app.members()) {
      const item:Member = Member.app.members()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Member CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        member_ids: Member.selectedMembers().map((member) => member.id)
      }
      console.log('Member IDS', body)
      // TODO: post member_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  selected: ko.Observable<boolean>

  constructor(options: MemberOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
  }

  static newMember(app:App,e:MouseEvent) {
    console.log('NEW Member CLICK',app,e)
    const new_member = new Member({id:0, name:'...'})
    app.selectedMember(new_member)
  }

  static loadData(members:ko.ObservableArray<Member>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Member.app.api_token(),
      }
    }
    fetch(Member.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      members([])
      data.members.forEach((member:MemberOptions) => {
        members.push(new Member(member))
      })
    })
  }

  select(this:App,self:Member,e:MouseEvent) {
    this.selectedMember(null)
    const url = `${Member.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Member.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Member DATA', data)
      self.id = data.member.id
      self.name(data.member.name)
      this.selectedMember(self)
    })
  }

  cancel() {
    console.log('Member CANCEL CLICK', this)
  }

  save(self:Member, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Member SAVE CLICK',e, this)
    const url = `${Member.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Member.app.api_token(),
      },
      body: data
    }
    console.log('Member POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Member DATA POST', data)
      const modalElement = document.getElementById('memberModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Member.app.members.push(new Member(data.member))
      }
      bsModal.hide()
    })
  }

}