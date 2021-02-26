import ko from "knockout"

export interface TaskOptions {
  id?:number
  name?:string
  date?:string
  time?:string
  company_id?:number
  companiesList?:any
  farm_id?:number
  farmsList?:any
  parcel_id?:number
  parcelsList?:any
  plot_id?:number
  plotsList?:any
  user?:string
  team_id?:number
  teamsList?:any
  member_id?:number
  membersList?:any
  contact?:string
  move_type?:string
  moveTypeValues?: SelectOptions[]
  categoriesList?:any
  category_id?:number
  product_id?:number
  productsList?:any
  description?:string
  uom?:string
  quantity?:number
  price?:number
  notes?:string
  taxes?:any
}
export class Task {
  static api_url = '/api/v1/tasks'
  //#region Declaraciones
  id: number
  name: ko.Observable<string>
  date: ko.Observable<string>
  time: ko.Observable<string>
  company_id: ko.Observable<number>
  companiesList: ko.ObservableArray<any>
  farm_id: ko.Observable<number>
  farmsList: ko.ObservableArray<any>
  parcel_id: ko.Observable<number>
  parcelsList: ko.ObservableArray<any>
  plot_id: ko.Observable<number>
  plotsList: ko.ObservableArray<any>
  user: ko.Observable<string>
  team_id: ko.Observable<number>
  teamsList: ko.ObservableArray<any>
  member_id: ko.Observable<number>
  membersList: ko.ObservableArray<any>
  contact: ko.Observable<string>
  move_type: ko.Observable<string>
  moveTypeValues: SelectOptions[]
  category_id: ko.Observable<number>
  categoriesList: ko.ObservableArray<any>
  product_id: ko.Observable<number>
  productsList: ko.ObservableArray<any>
  description: ko.Observable<string>
  uom: ko.Observable<string>
  quantity: ko.Observable<number>
  price: ko.Observable<number>
  notes: ko.Observable<string>

  taxesList: ko.ObservableArray<any>
  taxes: ko.ObservableArray<any>

  isDirty: ko.Computed

  static columns = ['date',
    'time',
    'user',
    'contact',
    'move_type',
    'name',
    'description',
    'uom',
    'quantity',
    'price',
    'notes',
    'taxes',
    'company_id',
    'farm_id',
    'parcel_id',
    'plot_id',
    'team_id',
    'member_id',
    'category_id',
    'product_id',
]

  //#endregion
  constructor(options:TaskOptions={}) {

    this.id = options.id||0
    this.date = ko.observable(options.date||'').extend({ trackChange: false } as any)
    this.time = ko.observable(options.time||'').extend({ trackChange: false } as any)
    this.company_id = ko.observable(options.company_id||0).extend({ trackChange: false } as any)
    this.farm_id = ko.observable(options.farm_id||0).extend({ trackChange: false } as any)
    this.parcel_id = ko.observable(options.parcel_id||0).extend({ trackChange: false } as any)
    this.plot_id = ko.observable(options.plot_id||0).extend({ trackChange: false } as any)
    this.user = ko.observable(options.user||'').extend({ trackChange: false } as any)
    this.team_id = ko.observable(options.team_id||0).extend({ trackChange: false } as any)
    this.member_id = ko.observable(options.member_id||0).extend({ trackChange: false } as any)
    this.contact = ko.observable(options.contact||'').extend({ trackChange: false } as any)
    this.move_type = ko.observable(options.move_type||'')    .extend({ trackChange: false } as any)
    this.category_id = ko.observable(options.category_id||0).extend({ trackChange: false } as any)
    this.product_id = ko.observable(options.product_id||0).extend({ trackChange: false } as any)
    this.name = ko.observable(options.name||'').extend({ trackChange: false } as any)
    this.description = ko.observable(options.description||'').extend({ trackChange: false } as any)
    this.uom = ko.observable(options.uom||'').extend({ trackChange: false } as any)
    this.quantity = ko.observable(options.quantity||1).extend({ trackChange: false } as any)
    this.price = ko.observable(options.quantity||0).extend({ trackChange: false } as any)
    this.notes = ko.observable(options.notes||'').extend({ trackChange: false } as any)
    this.taxes = ko.observableArray().extend({ trackChange: false } as any)

    this.moveTypeValues = options.moveTypeValues||[{id:'in',text:'INGRESO/VENTA'},{id:'out',text:'GASTO/COMPRA'}]
    this.companiesList = ko.observableArray(options.companiesList||[])
    this.farmsList = ko.observableArray(options.farmsList||[])
    this.parcelsList = ko.observableArray(options.parcelsList||[])
    this.plotsList = ko.observableArray(options.plotsList||[])
    this.teamsList = ko.observableArray(options.teamsList||[])
    this.membersList = ko.observableArray(options.membersList||[])
    this.categoriesList = ko.observableArray(options.categoriesList||[])
    this.productsList = ko.observableArray(options.productsList||[])
    this.taxesList = ko.observableArray([])

    this.isDirty = ko.pureComputed(() => {
      console.log('USER', this.user)
      return true
    })


    this.fetchData().then(response => response.json())
    .then(data => this.setData(data)).catch(this.manageError)
  }

  fetchData() {
    const url = `${Task.api_url}/${this.id}`
    const options = {
      method: 'GET',
    }
    return fetch(url,options)
  }

  setData(data:any={}) {
    let task:TaskOptions = data.task
    this.id = task.id||0
    this.date(task.date||'')
    this.time(task.time||'')
    this.user((data.user||{})['name']||'')

    this.contact(task.contact||'')
    this.move_type(task.move_type||'')
    this.name(task.name||'')
    this.description(task.description||'')
    this.uom(task.uom||'')
    this.quantity(task.quantity||1)
    this.price(task.price||0)
    this.notes(task.notes||'')
    this.taxes(task.taxes||[])
    this.taxesList(data.taxesList)

    this.companiesList(data.companiesList||[])
    this.company_id(task.company_id)

    this.farmsList(data.farmsList||[])
    this.farm_id(task.farm_id)

    this.parcelsList(data.parcelsList||[])
    this.parcel_id(task.parcel_id)

    this.plotsList(data.plotsList||[])
    this.plot_id(task.plot_id)

    this.teamsList(data.teamsList||[])
    this.team_id(task.team_id||0)

    this.membersList(data.membersList||[])
    this.member_id(task.member_id||0)

    this.categoriesList(data.categoriesList||[])
    this.category_id(task.category_id||0)

    this.productsList(data.productsList||[])
    this.product_id(task.product_id||0);
    Task.columns.forEach(i => {
      this[i].extend({trackChange:true})
      //console.log('I',this[i])
    })
  }

  manageError(err) {
    console.error('ERROR:', err)
  }

}