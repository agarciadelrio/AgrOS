import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface PlotOptions {
  id: number
  name: string
  code: any
  description: any
  surface: any
  dripper_lines: any
  dripper_gap: any
}

export class Plot {
  static app:App
  static api_url = '/api/v1/plots'

  static someSelectedPlot = ko.pureComputed(() => {
    for(let _i in Plot.app.plots()) {
      const item:Plot = Plot.app.plots()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedPlots = ko.pureComputed(() => {
    return Plot.app.plots().filter((item:Plot) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Plot CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Plot.app.plots()) {
      const item:Plot = Plot.app.plots()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Plot CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        plot_ids: Plot.selectedPlots().map((plot) => plot.id)
      }
      console.log('Plot IDS', body)
      // TODO: post plot_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  code: ko.Observable<any>
  description: ko.Observable<any>
  surface: ko.Observable<any>
  dripper_lines: ko.Observable<any>
  dripper_gap: ko.Observable<any>

  selected: ko.Observable<boolean>

  constructor(options: PlotOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
    this.code = ko.observable(options.code)
    this.description = ko.observable(options.description)
    this.surface = ko.observable(options.surface)
    this.dripper_lines = ko.observable(options.dripper_lines)
    this.dripper_gap = ko.observable(options.dripper_gap)
  }

  static newPlot(app:App,e:MouseEvent) {
    console.log('NEW Plot CLICK',app,e)
    const new_plot = new Plot({
      id:0,
      name:'',
      code:'',
      description:'',
      surface:'',
      dripper_lines:'',
      dripper_gap:'',
    })
    app.selectedPlot(new_plot)
  }

  static loadData(plots:ko.ObservableArray<Plot>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Plot.app.api_token(),
      }
    }
    fetch(Plot.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      plots([])
      data.plots.forEach((plot:PlotOptions) => {
        plots.push(new Plot(plot))
      })
    })
  }

  select(this:App,self:Plot,e:MouseEvent) {
    this.selectedPlot(null)
    const url = `${Plot.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Plot.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Plot DATA', data)
      self.id = data.plot.id
      self.name(data.plot.name)
      self.code(data.plot.code)
      self.description(data.plot.description)
      self.surface(data.plot.surface)
      self.dripper_lines(data.plot.dripper_lines)
      self.dripper_gap(data.plot.dripper_gap)
      this.selectedPlot(self)
    })
  }

  cancel() {
    console.log('Plot CANCEL CLICK', this)
  }

  save(self:Plot, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Plot SAVE CLICK',e, this)
    const url = `${Plot.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Plot.app.api_token(),
      },
      body: data
    }
    console.log('Plot POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Plot DATA POST', data)
      const modalElement = document.getElementById('plotModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Plot.app.plots.push(new Plot(data.plot))
      }
      bsModal.hide()
    })
  }

}