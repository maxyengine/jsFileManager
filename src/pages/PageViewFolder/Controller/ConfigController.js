import { Value } from '@nrg/core'
import { ACTION_LOAD_CONFIG } from '../constants'

export default class ConfigController extends Value {

  static get services () {
    return {
      client: 'client'
    }
  }

  get assignments () {
    return [
      'loadConfig'
    ]
  }

  get state () {
    return this.owner.state
  }

  action (...args) {
    this.owner.action(...args)
  }

  async loadConfig () {
    const config = await this.client.fetchConfig()
    this.action(ACTION_LOAD_CONFIG, {config})
  }
}