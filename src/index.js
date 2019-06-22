import '@babel/polyfill'
import 'whatwg-fetch'
import './styles/fonts.scss'
import React from 'react'
import ReactDOM from 'react-dom'
import Session from './services/Session'
import AuthControl from './services/AuthControl'
import Client from './services/Client'
import FileFactory from './services/FileFactory'
import PageUploader from './pages/PageUploader'
import PageViewFolder from './pages/PageViewFolder'
import PageLogin from './pages/PageLogin'
import NavMenu from './components/NavMenu'
import PrivateRoute from './components/PrivateRoute'
import { MemoryRouter as Router, Route } from 'react-router-dom'
import { ServiceLocator, createInjector } from '@nrg/react-di'
import { Endpoint } from '@nrg/http'
import { Value } from '@nrg/core'
import { apiUrl } from './devConfig'

window.$nrg = window.$nrg || {}
var $nrg = window.$nrg

$nrg.Uploader = $nrg.Uploader || class extends Value {

  get defaults () {
    return {
      apiUrl: '.',
      wrapper: document.body
    }
  }

  async run () {

    const injector = createInjector({
      endpoint: [Endpoint, {apiUrl: this.apiUrl}],
      session: [Session, {name: '$nrg.Uploader'}],
      client: Client,
      authControl: AuthControl,
      fileFactory: FileFactory
    })

    const client = injector.getService('client')
    const authControl = injector.getService('authControl')
    const {authorization, directFileAccess, directFileUrl} = await client.fetchConfig()

    client.set({directFileAccess, directFileUrl})
    authControl.set({authorization})

    const app = (
      <Router>
        <ServiceLocator injector={injector}>

          <NavMenu/>

          <PrivateRoute exact path="/" component={PageUploader}/>
          <PrivateRoute exact path="/view-folder" component={PageViewFolder}/>
          <Route path="/login" component={PageLogin}/>

        </ServiceLocator>
      </Router>
    )

    ReactDOM.render(app, this.wrapper)
  }
}

// dev
new $nrg.Uploader({apiUrl: apiUrl, wrapper: document.getElementById('app')}).run()

// build
//new $nrg.Uploader({wrapper: document.getElementById('app')}).run()
