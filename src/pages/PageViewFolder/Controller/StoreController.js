import { Object } from '@nrg/core'
import { createStore } from 'redux'
import { composeWithDevTools } from 'redux-devtools-extension'

const store = Symbol()

export default class StoreController extends Object {

  constructor (...args) {
    super(...args)
    this[store] = createStore((state = this.initState || {}, action) => action.state || state, composeWithDevTools())
  }

  get store () {
    return this[store]
  }

  get state () {
    return this.store.getState()
  }

  action (type, diff) {
    this.store.dispatch({type, state: {...this.state, ...diff}})
  }
}