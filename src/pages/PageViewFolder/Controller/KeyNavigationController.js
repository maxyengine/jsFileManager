import TraitController from './TraitController'
import { ACTION_ACTIVATE_ITEM } from '../constants'
import Directory from '../../../entities/Directory'

export default class KeyNavigationController extends TraitController {

  static get services () {
    return {
      client: 'client'
    }
  }

  get assignments () {
    return [
      'activateNextItem',
      'activatePrevItem',
      'activateFirstItem',
      'activateLastItem',
      'activateItem',
      'openActiveFile'
    ]
  }

  activateNextItem () {
    let {activeIndex, files, directory} = this.state
    const maxIndex = directory.isRoot ? files.length - 1 : files.length

    if (activeIndex < maxIndex) {
      this.activateItem(++activeIndex)
    }
  }

  activatePrevItem () {
    let {activeIndex} = this.state

    if (activeIndex > 0) {
      this.activateItem(--activeIndex)
    }
  }

  activateFirstItem () {
    this.activateItem(0)
  }

  activateLastItem () {
    let {files, directory} = this.state

    this.activateItem(directory.isRoot ? files.length - 1 : files.length)
  }

  activateItem (activeIndex) {
    this.action(ACTION_ACTIVATE_ITEM, {activeIndex})
  }

  openActiveFile (history) {
    const file = this.getActiveFile()

    if (file instanceof Directory) {
      history.push(`/?path=${encodeURIComponent(file.path.value)}`)
    } else {
      this.client.openFile(file.path.value)
    }
  }

  getActiveFile () {
    let {activeIndex, files, directory} = this.state

    if (directory.isRoot) {
      return files[activeIndex]
    }

    return activeIndex ? files[activeIndex - 1] : directory.parent
  }
}