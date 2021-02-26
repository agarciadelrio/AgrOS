import ko from 'knockout'
import $ from 'jquery'

interface DataTableOptions {
  src: string
  limit: number
  page: number
  set: string
  order: string
}

interface LoadOptions {
  limit?: number
  page?: number
  order?: string
}

export class DataTable {
  static label:string ='DEMO'
  items: ko.ObservableArray<any>
  api_url: string
  feed_url: string
  data_set: string
  limit: ko.Observable<number>
  page: ko.Observable<number>
  order: ko.Observable<string>
  title: ko.Observable<string>
  order_columns: ko.Observable<{}>
  data_options: DataTableOptions
  order_string: (() => string)

  constructor(api_url:string, element:JQuery<HTMLElement>) {
    let options = element.data() as DataTableOptions
    let columns = $.map(
      element.find('thead th[data-column]'),
      c => (<HTMLElement>c).getAttribute('data-column')
    )
    //console.log('COLUMNS',columns)
    this.api_url = api_url
    this.data_options = options
    this.feed_url = `${this.api_url}/${this.data_options.src}/${this.data_options.set}`
    this.data_set = this.data_options.set
    this.limit = ko.observable(this.data_options.limit || 10)
    this.page = ko.observable(this.data_options.page || 1)
    this.order = ko.observable(this.data_options.order || '')
    this.title = ko.observable(this.data_set)
    this.items = ko.observableArray([])
    this.order_columns = ko.observable({})
    this.order_string = () => {
      let out = []
      for (let key in this.order_columns()){
        let i = this.order_columns()[key]
        if(i>0) out.push(`${key}:${i}`)
      }
      return out.join(',')
    }
    this._loadData()
    this.page.subscribe(this._loadData.bind(this))
    this.limit.subscribe(this._loadData.bind(this))
    this.order.subscribe(this._loadData.bind(this))
  }
  _loadData() {
    return this.loadData({
      limit: this.limit(),
      page: this.page(),
      order: this.order_string(),
    })
  }
  loadData(options) {
    return this.fetchData(options).then(data => {
      //console.log('DATA', data)
      this.items(data[this.data_set]||[])
    }).catch(err => {
      console.error('ERROR', err)
    });
  }
  fetchData(options:LoadOptions) {
    const q:string = Object.keys(options).map(key => key + '=' + options[key]).join('&');
    const url = `${this.feed_url}?${q}`
    //console.log('URL', url)
    return fetch(url).then(response => response.json())
  }
  nextPage() {
    let next = this.page()+1
    this.page(next)
    //this._loadData()
  }
  prevPage() {
    let prev = this.page()-1
    if(prev<1) prev=1
    this.page(prev)
    //this._loadData()
  }
  ord(this:string,self:DataTable,e:MouseEvent) {
    console.log('ORD')
    //console.log('this',this)
    self.order_columns()[this] = self.order_columns()[this] || 0
    var val = self.order_columns()[this] = (self.order_columns()[this] + 1)%3
    self.order(self.order_string())
    const $th = $(e.target)
    console.log('TH', $th)
    if(val==0) {
      $th.removeClass('ASC DESC')
    } else if(val==1) {
      $th.removeClass('DESC').addClass('ASC')
    } else if(val==2) {
      $th.removeClass('ASC').addClass('DESC')
    }
    console.log('e', e, val)
    console.log('order', self.order())
  }
}
