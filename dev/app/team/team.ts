import ko from 'knockout'
import * as bootstrap from 'bootstrap'
import { App } from '../../app'

export interface TeamOptions {
  id: number
  name: string
  personal_team: any
}

export class Team {
  static app:App
  static api_url = '/api/v1/teams'

  static someSelectedTeam = ko.pureComputed(() => {
    for(let _i in Team.app.teams()) {
      const item:Team = Team.app.teams()[_i]
      if(item.selected()) {
        return true
      }
    }
    return false
  })

  static selectedTeams = ko.pureComputed(() => {
    return Team.app.teams().filter((item:Team) => item.selected())
  })

  static deselectAll = (app:App, e:MouseEvent) => {
    console.log('Team CLICK DESELCT ALL',e, e.altKey)
    for(let _i in Team.app.teams()) {
      const item:Team = Team.app.teams()[_i]
      if(e.altKey) {
        item.selected(true)
      } else {
        item.selected(!item.selected())
      }
    }
  }

  static deleteSelected = (app:App, e:MouseEvent) => {
    if(confirm('Are you sure you want to delete these items?')) {
      console.log('Team CLICK DELETE SELECTED',e, e.altKey)
      const body = {
        action: 'delete',
        team_ids: Team.selectedTeams().map((team) => team.id)
      }
      console.log('Team IDS', body)
      // TODO: post team_ids for delete to the PHP-API
    }
  }

  id: number
  name: ko.Observable<string>
  personal_team: ko.Observable<any>

  selected: ko.Observable<boolean>

  constructor(options: TeamOptions) {
    this.selected = ko.observable(false)
    this.id = options.id
    this.name = ko.observable(options.name)
    this.personal_team = ko.observable(options.personal_team)
  }

  static newTeam(app:App,e:MouseEvent) {
    console.log('NEW Team CLICK',app,e)
    const new_team = new Team({
      id:0,
      name:'',
      personal_team:'',
    })
    app.selectedTeam(new_team)
  }

  static loadData(teams:ko.ObservableArray<Team>) {
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Team.app.api_token(),
      }
    }
    fetch(Team.api_url,options).then((response) => {
      return response.json()
    }).then((data) => {
      teams([])
      data.teams.forEach((team:TeamOptions) => {
        teams.push(new Team(team))
      })
    })
  }

  select(this:App,self:Team,e:MouseEvent) {
    this.selectedTeam(null)
    const url = `${Team.api_url}/${self.id}`
    const options = {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Team.app.api_token(),
      }
    }
    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Team DATA', data)
      self.id = data.team.id
      self.name(data.team.name)
      self.personal_team(data.team.personal_team)
      this.selectedTeam(self)
    })
  }

  cancel() {
    console.log('Team CANCEL CLICK', this)
  }

  save(self:Team, e:MouseEvent) {
    e.stopPropagation()
    e.preventDefault()
    console.log('Team SAVE CLICK',e, this)
    const url = `${Team.api_url}/${this.id}`
    const data = ko.toJSON(this)
    const options = {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'API_TOKEN:' + Team.app.api_token(),
      },
      body: data
    }
    console.log('Team POST', options)

    fetch(url,options).then((response) => {
      return response.json()
    }).then((data) => {
      console.log('Team DATA POST', data)
      const modalElement = document.getElementById('teamModal')
      const bsModal = (<any>bootstrap.Modal).getInstance(modalElement)
      if(data.mode=='create') {
        Team.app.teams.push(new Team(data.team))
      }
      bsModal.hide()
    })
  }

}