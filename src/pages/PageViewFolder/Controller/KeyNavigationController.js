import TraitController from './TraitController'
import { ACTION_ACTIVATE_ITEM } from '../constants'

export default class KeyNavigationController extends TraitController {

  get assignments () {
    return [
      'activateNextItem',
      'activatePrevItem'
    ]
  }

  activateNextItem () {
    let {activeIndex, files} = this.state

    if (activeIndex < files.length - 1) {
      activeIndex++
      this.action(ACTION_ACTIVATE_ITEM, {activeIndex})
    }
  }

  activatePrevItem () {
    let {activeIndex} = this.state

    if (activeIndex >= 0) {
      activeIndex--
      this.action(ACTION_ACTIVATE_ITEM, {activeIndex})
    }
  }
}