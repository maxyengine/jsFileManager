import '@babel/polyfill'
import 'whatwg-fetch'
import './styles/fonts.scss'
import React from 'react'
import ReactDOM from 'react-dom'
import Session from './services/Session'
import AuthControl from './services/AuthControl'
import Client from './services/Client'
import FileFactory from './services/FileFactory'
// import PageUploader from './pages/PageUploader'
import PageViewFolder from './pages/PageViewFolder'
import PageLogin from './pages/PageLogin'
// import NavMenu from './components/NavMenu'
import PrivateRoute from './components/PrivateRoute'
import { BrowserRouter as Router, Route } from 'react-router-dom'
import { ServiceLocator, createInjector } from '@nrg/react-di'
import { Endpoint } from '@nrg/http'
import { Value } from '@nrg/core'
import { apiUrl } from './devConfig'
import AppWrapper from './components/AppWrapper'

window.$nrg = window.$nrg || {}
var $nrg = window.$nrg

$nrg.Uploader = $nrg.Uploader || class extends Value {

  get defaults () {
    return {
      apiUrl: '.',
      wrapper: document.body,
      height: null
    }
  }

  async run () {

    const injector = createInjector({
      endpoint: [Endpoint, {apiUrl: this.apiUrl}],
      session: [Session, {name: '$nrg.Uploader'}],
      client: Client,
      authControl: AuthControl,
      fileFactory: FileFactory,
      appHeight: this.height,
      appWrapper: this.wrapper
    })

    const client = injector.getService('client')
    const authControl = injector.getService('authControl')
    const {authorization, directFileAccess, directFileUrl} = await client.fetchConfig()

    client.set({directFileAccess, directFileUrl})
    authControl.set({authorization})

    const app = (
      <Router>
        <ServiceLocator injector={injector}>
          <AppWrapper>
            {/*<NavMenu/>
            <PrivateRoute exact path="/" component={PageUploader}/>
            <PrivateRoute exact path="/view-folder" component={PageViewFolder}/>
            <Route path="/login" component={PageLogin}/>*/}

            <PrivateRoute exact path="/" component={PageViewFolder}/>
            <Route path="/login" component={PageLogin}/>
          </AppWrapper>

        </ServiceLocator>
      </Router>
    )

    ReactDOM.render(app, this.wrapper)
  }
}

// dev
new $nrg.Uploader({
  apiUrl: apiUrl,
  wrapper: document.getElementById('app'),
  height: 800
}).run()

// build
//new $nrg.Uploader({wrapper: document.getElementById('app')}).run()
