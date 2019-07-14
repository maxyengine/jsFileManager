import React from 'react'
import { Provider } from 'react-redux'
import { inject } from '@nrg/react-di'
import Controller from './Controller'
import NewFolderModal from './NewFolderModal'
import Toolbar from './Toolbar'
import Breadcrumbs from './Breadcrumbs'
import FileList from './FileList'
import UploadFilesModal from './UploadFilesModal'
import { HotKeys } from 'react-hotkeys'

class PageViewFolder extends React.Component {

  keyMap = {
    prev: 'up',
    next: 'down'
  }

  handlers = {
    next: this.props.controller.activateNextItem,
    prev: this.props.controller.activatePrevItem
  }

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
        <HotKeys keyMap={this.keyMap} handlers={this.handlers}>
          <Toolbar/>
          <Breadcrumbs/>
          <FileList/>
        </HotKeys>
        <NewFolderModal/>
        <UploadFilesModal/>
      </Provider>
    )
  }
}

const dependencies = {controller: Controller}

export default inject(PageViewFolder, dependencies)
