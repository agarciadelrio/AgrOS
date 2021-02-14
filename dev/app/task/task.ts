import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface TaskOptions {
  id: number
  name: string
}

export class Task {
  static app:App
  static api_url = '/api/v1/tasks'

  static someSelectedTask = ko.pureComputed(() => {
    for(let _i in Task.app.tasks()) {
      const item:Task = Task.app.tasks()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedTasks = ko.pureComputed(() => {
    return Task.app.tasks().filter((item:Task) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Task CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Task.app.tasks()) {
      const item:Task = Task.app.tasks()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Task CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        task_ids: Task.selectedTasks().map((task) => task.id)
      }
      console.log('Task IDS', body)
      // TODO: post task_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  selected: ko.Observable<boolean>

  constructor(options: TaskOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
  }

  static newTask(app:App,e:MouseEvent) {
    console.log('NEW Task CLICK',app,e)
    const new_task = new Task({id:0, name:'...'})
    app.selectedTask(new_task)
  }

  static loadData(tasks:ko.ObservableArray<Task>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Task.app.api_token(),
      }
    }
    fetch(Task.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      tasks([])
      data.tasks.forEach((task:TaskOptions) => {
        tasks.push(new Task(task))
      })
    })
  }

  select(this:App,self:Task,e:MouseEvent) {
    this.selectedTask(null)
    const url = `${Task.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Task.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Task DATA', data)
      self.id = data.task.id
      self.name(data.task.name)
      this.selectedTask(self)
    })
  }

  cancel() {
    console.log('Task CANCEL CLICK', this)
  }

  save(self:Task, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Task SAVE CLICK',e, this)
    const url = `${Task.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Task.app.api_token(),
      },
      body: data
    }
    console.log('Task POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Task DATA POST', data)
      const modalElement = document.getElementById('taskModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Task.app.tasks.push(new Task(data.task))
      }
      bsModal.hide()
    })
  }

}