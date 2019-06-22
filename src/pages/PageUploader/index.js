import React from 'react'
import { Provider } from 'react-redux'
import { inject } from '@nrg/react-di'
import Controller from './Controller'
import Page from '../../components/Page'
import DragArea from './DragArea'
import StatusBar from './StatusBar'
import FileList from './FileList'

const Component = class extends React.Component {

  state = {
    isReady: false,
    error: null
  }

  constructor (props) {
    super(props)
    this.controller = this.props.controller
  }

  async componentDidMount () {
    try {
      await this.controller.loadConfig()
      this.setState({isReady: true})
    } catch (error) {
      this.setState({error})
    }
  }

  render () {
    const {isReady, error} = this.state

    return (
      <Page {...{isReady, error}}>
        <Provider store={this.controller.store}>
          <DragArea/>
          <StatusBar/>
          <FileList/>
        </Provider>
      </Page>
    )
  }
}

const dependencies = {controller: Controller}

export default inject(Component, dependencies)
