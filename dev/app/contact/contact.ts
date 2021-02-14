import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface ContactOptions {
  id: number
  name: string
}

export class Contact {
  static app:App
  static api_url = '/api/v1/contacts'

  static someSelectedContact = ko.pureComputed(() => {
    for(let _i in Contact.app.contacts()) {
      const item:Contact = Contact.app.contacts()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedContacts = ko.pureComputed(() => {
    return Contact.app.contacts().filter((item:Contact) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Contact CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Contact.app.contacts()) {
      const item:Contact = Contact.app.contacts()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Contact CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        contact_ids: Contact.selectedContacts().map((contact) => contact.id)
      }
      console.log('Contact IDS', body)
      // TODO: post contact_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  selected: ko.Observable<boolean>

  constructor(options: ContactOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
  }

  static newContact(app:App,e:MouseEvent) {
    console.log('NEW Contact CLICK',app,e)
    const new_contact = new Contact({id:0, name:'...'})
    app.selectedContact(new_contact)
  }

  static loadData(contacts:ko.ObservableArray<Contact>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Contact.app.api_token(),
      }
    }
    fetch(Contact.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      contacts([])
      data.contacts.forEach((contact:ContactOptions) => {
        contacts.push(new Contact(contact))
      })
    })
  }

  select(this:App,self:Contact,e:MouseEvent) {
    this.selectedContact(null)
    const url = `${Contact.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Contact.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Contact DATA', data)
      self.id = data.contact.id
      self.name(data.contact.name)
      this.selectedContact(self)
    })
  }

  cancel() {
    console.log('Contact CANCEL CLICK', this)
  }

  save(self:Contact, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Contact SAVE CLICK',e, this)
    const url = `${Contact.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Contact.app.api_token(),
      },
      body: data
    }
    console.log('Contact POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Contact DATA POST', data)
      const modalElement = document.getElementById('contactModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Contact.app.contacts.push(new Contact(data.contact))
      }
      bsModal.hide()
    })
  }

}