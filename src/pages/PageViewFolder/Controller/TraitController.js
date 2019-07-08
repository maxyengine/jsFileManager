import { Value } from '@nrg/core'

export default class TraitController extends Value {

  get state () {
    return this.owner.state
  }

  action (...args) {
    this.owner.action(...args)
  }
}