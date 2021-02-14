import '@fortawesome/fontawesome-free/css/all.css'
import ko, { options } from 'knockout'
import $ from 'jquery'
import { Authorization } from './app/authorization/authorization'
import { Company } from './app/company/company'
import { Contact } from './app/contact/contact'
import { Farm } from './app/farm/farm'
import { Handler } from './app/handler/handler'
import { Item } from './app/item/item'
import { Member } from './app/member/member'
import { Parcel } from './app/parcel/parcel'
import { Permission } from './app/permission/permission'
import { Plot } from './app/plot/plot'
import { Product } from './app/product/product'
import { Profile } from './app/profile/profile'
import { Property } from './app/property/property'
import { Stuff } from './app/stuff/stuff'
import { Task } from './app/task/task'
import { Taxonomy } from './app/taxonomy/taxonomy'
import { Team } from './app/team/team'
import { User } from './app/user/user'
import { Variety } from './app/variety/variety'

console.log('APP 1.0', ko)

ko.bindingHandlers.fadeVisible = {
  init: function(element, valueAccessor) {
      // Initially set the element to be instantly visible/hidden depending on the value
      var value = valueAccessor()
      $(element).toggle(ko.unwrap(value)) // Use "unwrapObservable" so we can handle values that may or may not be observable
  },
  update: function(element, valueAccessor) {
      // Whenever the value subsequently changes, slowly fade the element in or out
      var value = valueAccessor()
      ko.unwrap(value) ? $(element).fadeIn('fast') : $(element).hide()
  }
}

ko.bindingHandlers.setAppState = {
  'init': function (element:HTMLAnchorElement, valueAccessor:any, allBindings,
    viewModel:App, bindingContext) {
    var value = valueAccessor()
    var newValueAccesssor = function() {
      return function (app:App,e:MouseEvent) {
        app.setState(value, app, e)
        return false
      }
    }
    ko.bindingHandlers.click.init(
      element, newValueAccesssor, allBindings, viewModel, bindingContext)
  }
}

export class App {
  //#region Declaraciones

  static api_sessions_url = '/api/v1/sessions'
  static icons = {
    authorization: 'key',
    company: 'building',
    contact: 'user',
    dashboard: 'tachometer-alt',
    desktop: 'desktop',
    farm: 'warehouse',
    handler: 'user-cog',
    item: 'archive',
    member: 'user',
    notebook: 'book-open',
    parcel: 'vector-square',
    permission: 'user-check',
    plot: 'square',
    product: 'box',
    profile: 'address-card',
    property: 'home',
    stuff: 'archive',
    task: 'tasks',
    taxonomy: 'tag',
    team: 'users',
    user: 'user',
    variety: 'seedling',
  }

  api_token: ko.Observable<string|null>
  user: ko.Observable<User>
  state: ko.Observable<string>
  sub_state: ko.Observable<string>

  authorizations: ko.ObservableArray<Authorization>
  companies: ko.ObservableArray<Company>
  contacts: ko.ObservableArray<Contact>
  farms: ko.ObservableArray<Farm>
  handlers: ko.ObservableArray<Handler>
  items: ko.ObservableArray<Item>
  members: ko.ObservableArray<Member>
  parcels: ko.ObservableArray<Parcel>
  permissions: ko.ObservableArray<Permission>
  plots: ko.ObservableArray<Plot>
  products: ko.ObservableArray<Product>
  profiles: ko.ObservableArray<Profile>
  properties: ko.ObservableArray<Property>
  stuffs: ko.ObservableArray<Stuff>
  taxonomies: ko.ObservableArray<Taxonomy>
  tasks: ko.ObservableArray<Task>
  teams: ko.ObservableArray<Team>
  users: ko.ObservableArray<User>
  varieties: ko.ObservableArray<Variety>

  selectedAuthorization: ko.Observable<Authorization>
  selectedCompany: ko.Observable<Company>
  selectedContact: ko.Observable<Contact>
  selectedFarm: ko.Observable<Farm>
  selectedHandler: ko.Observable<Handler>
  selectedItem: ko.Observable<Item>
  selectedMember: ko.Observable<Member>
  selectedParcel: ko.Observable<Parcel>
  selectedPermission: ko.Observable<Permission>
  selectedPlot: ko.Observable<Plot>
  selectedProduct: ko.Observable<Product>
  selectedProfile: ko.Observable<Profile>
  selectedProperty: ko.Observable<Property>
  selectedStuff: ko.Observable<Stuff>
  selectedTask: ko.Observable<Task>
  selectedTaxonomy: ko.Observable<Taxonomy>
  selectedTeam: ko.Observable<Team>
  selectedUser: ko.Observable<User>
  selectedVariety: ko.Observable<Variety>

  //#endregion

  constructor(state:string) {
    this.api_token = ko.observable(null)
    this.user = ko.observable(null)
    this.state = ko.observable('')
    this.sub_state = ko.observable('')

    this.authorizations = ko.observableArray([])
    this.companies = ko.observableArray([])
    this.contacts = ko.observableArray([])
    this.farms = ko.observableArray([])
    this.handlers = ko.observableArray([])
    this.items = ko.observableArray([])
    this.members = ko.observableArray([])
    this.parcels = ko.observableArray([])
    this.permissions = ko.observableArray([])
    this.plots = ko.observableArray([])
    this.products = ko.observableArray([])
    this.profiles = ko.observableArray([])
    this.properties = ko.observableArray([])
    this.stuffs = ko.observableArray([])
    this.tasks = ko.observableArray([])
    this.taxonomies = ko.observableArray([])
    this.teams = ko.observableArray([])
    this.users = ko.observableArray([])
    this.varieties = ko.observableArray([])

    this.selectedAuthorization = ko.observable(null)
    this.selectedCompany = ko.observable(null)
    this.selectedContact = ko.observable(null)
    this.selectedFarm = ko.observable(null)
    this.selectedHandler = ko.observable(null)
    this.selectedItem = ko.observable(null)
    this.selectedMember = ko.observable(null)
    this.selectedPlot = ko.observable(null)
    this.selectedParcel = ko.observable(null)
    this.selectedPermission = ko.observable(null)
    this.selectedProduct = ko.observable(null)
    this.selectedProfile = ko.observable(null)
    this.selectedProperty = ko.observable(null)
    this.selectedStuff = ko.observable(null)
    this.selectedTask = ko.observable(null)
    this.selectedTaxonomy = ko.observable(null)
    this.selectedTeam = ko.observable(null)
    this.selectedUser = ko.observable(null)
    this.selectedVariety = ko.observable(null)

    this.initGlobals()
    this.setState(state)
  }

  initGlobals() {
    const u0 = {
      id:1,
      name:'Antonio',
      email:'agarciadelrio@gmail.com',
      password:'admin',
    }
    const a0 = 'JELOOOOOOOU'
    const u1 = {
      id:0,
      name:'',
      email:'',
      password:'',
    }
    const a1 = ''
    this.user(new User(u1))
    this.api_token(a1)
    Authorization.app = this
    Company.app = this
    Contact.app = this
    Farm.app = this
    Handler.app = this
    Item.app = this
    Member.app = this
    Parcel.app = this
    Permission.app = this
    Plot.app = this
    Product.app = this
    Profile.app = this
    Property.app = this
    Stuff.app = this
    Task.app = this
    Taxonomy.app = this
    Team.app = this
    User.app = this
    Variety.app = this

    window['Authorization'] = Authorization
    window['App'] = App
    window['Company'] = Company
    window['Contact'] = Contact
    window['Farm'] = Farm
    window['Handler'] = Handler
    window['Item'] = Item
    window['Member'] = Member
    window['Parcel'] = Parcel
    window['Permission'] = Permission
    window['Plot'] = Plot
    window['Product'] = Product
    window['Profile'] = Profile
    window['Property'] = Property
    window['Stuff'] = Stuff
    window['Task'] = Task
    window['Taxonomy'] = Taxonomy
    window['Team'] = Team
    window['User'] = User
    window['Variety'] = Variety
  }

  setState(state:string, app:App=null, e:MouseEvent=null) {
    this.state(state)
    this.sub_state('')
    switch (state) {
      case 'authorization':
        Authorization.loadData(this.authorizations)
        break;
      case 'company':
        Company.loadData(this.companies)
        break;
      case 'contact':
        Contact.loadData(this.contacts)
        break;
      case 'farm':
        Farm.loadData(this.farms)
        break;
      case 'handler':
        Handler.loadData(this.handlers)
        break;
      case 'item':
        Item.loadData(this.items)
        break;
      case 'member':
        Member.loadData(this.members)
        break;
      case 'parcel':
        Parcel.loadData(this.parcels)
        break;
      case 'permission':
        Permission.loadData(this.permissions)
        break;
      case 'plot':
        Plot.loadData(this.plots)
        break;
      case 'product':
        Product.loadData(this.products)
        break;
      case 'profile':
        Profile.loadData(this.profiles)
        break;
      case 'property':
        Property.loadData(this.properties)
        break;
      case 'stuff':
        Stuff.loadData(this.stuffs)
        break;
      case 'task':
        Task.loadData(this.tasks)
        break;
      case 'taxonomy':
        Taxonomy.loadData(this.taxonomies)
        break;
      case 'team':
        Team.loadData(this.teams)
        break;
      case 'user':
        User.loadData(this.users)
        break;
      case 'variety':
        Variety.loadData(this.varieties)
        break;
      case 'session':
      case 'signup':
      case 'login':
      case 'logout':
      case 'recover':
        if(state!=='session') {
          this.sub_state(state)
        }
        state = 'session'
        this.state(state)
      default:
        break
    }
    $('#menu a.nav-link.active').removeClass('active')
    $(`#menu a.nav-link[href='#${state}']`).addClass('active')
    if($('.navbar-collapse.collapse:visible').length) {
      $('.navbar-toggler:visible').trigger('click')
    }
    if(this.sub_state()=='logout') {
      this.logout()
    }
    console.log('click')
  }

  submitLogin() {
    console.log('SUBMIT LOGIN')
    const url = `${App.api_sessions_url}/login`
    const options = {
      method: 'POST',
      body: JSON.stringify({
        email: this.user().email(),
        password: this.user().password(),
      })
    }
    fetch(url, options).then(response => {
      return response.json()
    }).then(data => {
      console.log('DATA LOGIN POST', data)
      this.user(new User({
        id: data.user.id,
        name: data.user.name,
        email: data.user.email,
        password: null,
      }))
      this.api_token(data.user.api_token)
      this.setState('dashboard')
    }).catch(err => {
      console.error('ERROR', err)
    })
    return false
  }

  logout() {
    console.log('SUBMIT LOGOUT')
    let cookies = document.cookie.split(";");
    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i];
        let eqPos = cookie.indexOf("=");
        let name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
    this.user(new User({
      id: 0,
      name: '',
      email: '',
      password: ''
    }))
    this.api_token(null)
  }
}

$(function(){
  const default_state = 'login'
  const section = new URLSearchParams(document.location.search).get('section') || default_state
  window['app'] = new App(section)
  ko.applyBindings(window['app'])
  $('body > section.d-none').removeClass('d-none')
})
window.ko = ko
window['$'] = window['jQuery'] = $
