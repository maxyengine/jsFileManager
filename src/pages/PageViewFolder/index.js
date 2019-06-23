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

    /*props.history.listen((location, action) => {
      let params = new URLSearchParams(location.search);
      console.log(params.get('path'))
      console.log(action)
    });*/
  }

  async fetchDirectory (path) {
    const {controller} = this.props

    try {
      await controller.fetchUploadsFolder(path)
      this.setState({isReady: true})
    } catch (error) {
      this.setState({error})
    }
  }

  componentDidMount () {
    const path = new URLSearchParams(this.props.location.search).get('path')
    console.log('Mount: ' + path)
    this.fetchDirectory(path)
  }

  componentDidUpdate (prevProps) {
    const prevPath = new URLSearchParams(prevProps.location.search).get('path')
    const path = new URLSearchParams(this.props.location.search).get('path')

    console.log('Update: ' + prevPath + ' => ' + path)

    if (prevPath !== path) {
      this.setState({isReady: false})
      this.fetchDirectory(path)
    }
  }

  render () {
    const {isReady, error} = this.state
    const {controller} = this.props

    if (!isReady) {
      return <Page/>
    }

    return (
      <Page {...{isReady, error}}>
        <Provider store={controller.store}>
          <ViewFolder/>
        </Provider>
      </Page>
    )
  }
}

const dependencies = {controller: Controller}

export default inject(Component, dependencies)
