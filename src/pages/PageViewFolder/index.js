import React from 'react'
import { Provider } from 'react-redux'
import { inject } from '@nrg/react-di'
import Controller from './Controller'
import NewFolderModal from './NewFolderModal'
import Toolbar from './Toolbar'
import Breadcrumbs from './Breadcrumbs'
import FileList from './FileList'
import UploadFilesModal from './UploadFilesModal'

class PageViewFolder extends React.Component {

  async fetchData () {
    const {controller} = this.props
    const path = new URLSearchParams(this.props.location.search).get('path')

    await controller.fetchDirectory(path)
  }

  async componentDidMount () {
    const {controller} = this.props

    await controller.loadConfig()
    this.fetchData()
  }

  componentDidUpdate (prevProps) {
    this.fetchData()
  }

  render () {
    const {controller} = this.props

    return (
      <Provider store={controller.store}>
        <Toolbar/>
        <Breadcrumbs/>
        <FileList/>
        <NewFolderModal/>
        <UploadFilesModal/>
      </Provider>
    )
  }
}

const dependencies = {controller: Controller}

export default inject(PageViewFolder, dependencies)
