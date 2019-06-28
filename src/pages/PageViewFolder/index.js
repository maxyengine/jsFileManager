import React from 'react'
import { Provider } from 'react-redux'
import { inject } from '@nrg/react-di'
import Controller from './Controller'
import Page from '../../components/Page'
import ViewFolder from './ViewFolder'
import Modal from '../../components/Modal'
import NewFolderModal from './NewFolderModal'

class PageViewFolder extends React.Component {

  state = {
    isReady: false,
    error: null
  }

  async fetchDirectory (path) {
    const {controller} = this.props

    try {
      await controller.fetchDirectory(path)
      this.setState({isReady: true})
    } catch (error) {
      this.setState({error})
    }
  }

  componentDidMount () {
    const path = new URLSearchParams(this.props.location.search).get('path')
    this.fetchDirectory(path)
  }

  componentDidUpdate (prevProps) {
    const prevPath = new URLSearchParams(prevProps.location.search).get('path')
    const path = new URLSearchParams(this.props.location.search).get('path')

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
          <NewFolderModal/>
        </Provider>
      </Page>
    )
  }
}

const dependencies = {controller: Controller}

export default inject(PageViewFolder, dependencies)
