import React from 'react'
import { Provider } from 'react-redux'
import { inject } from '@nrg/react-di'
import Controller from './Controller'
import Page from '../../components/Page'
import ViewFolder from './ViewFolder'

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
      await this.controller.fetchUploadsFolder()
      this.setState({isReady: true})
    } catch (error) {
      this.setState({error})
    }
  }

  render () {
    const {isReady, error} = this.state

    if (!isReady) {
      return <Page/>
    }

    return (
      <Page {...{isReady, error}}>
        <Provider store={this.controller.store}>
          <ViewFolder/>
        </Provider>
      </Page>
    )
  }
}

const dependencies = {controller: Controller}

export default inject(Component, dependencies)
