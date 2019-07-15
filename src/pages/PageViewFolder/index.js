import React from 'react'
import { Provider } from 'react-redux'
import { inject } from '@nrg/react-di'
import Controller from './Controller'
import NewFolderModal from './NewFolderModal'
import Toolbar from './Toolbar'
import Breadcrumbs from './Breadcrumbs'
import FileList from './FileList'
import UploadFilesModal from './UploadFilesModal'

const ARROW_UP = 38
const ARROW_DOWN = 40
const ARROW_LEFT = 37
const ARROW_RIGHT = 39
const ENTER = 13

class PageViewFolder extends React.Component {

  async fetchData () {
    const {controller} = this.props
    const path = new URLSearchParams(this.props.location.search).get('path')

    await controller.fetchDirectory(path)

    controller.setFocusedComponent(this)
  }

  async componentDidMount () {
    const {controller} = this.props

    await controller.loadConfig()
    this.fetchData()

    document.addEventListener('keydown', event => {
      const {controller} = this.props

      if (controller.getFocusedComponent() === this) {
        this.onKeyDown(event)
      }
    })
  }

  onKeyDown = (event) => {
    event.preventDefault()

    const {controller, history} = this.props

    switch (event.keyCode) {
      case ARROW_UP:
        return controller.activatePrevItem()
      case ARROW_DOWN:
        return controller.activateNextItem()
      case ARROW_LEFT:
        return controller.activateFirstItem()
      case ARROW_RIGHT:
        return controller.activateLastItem()
      case ENTER:
        return controller.openActiveFile(history)
      default:
    }
  }

  componentDidUpdate (prevProps) {
    this.fetchData()
  }

  componentWillUnmount () {
    document.removeEventListener('keydown', this.onKeyDown)
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