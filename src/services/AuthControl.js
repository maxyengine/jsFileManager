import { Value } from '@nrg/core'

const data = Symbol()

export default class extends Value {

  static get services () {
    return {
      session: 'session'
    }
  }

  get defaults () {
    return {
      key: 'authControl',
      authorization: true
    }
  }

  login (payload) {
    this.session.write(this.key, payload)
  }

  logout () {
    this.session.remove(this.key)
  }

  get isGuest () {
    return this.authorization && !this.session.has(this.key)
  }

  get accessToken () {
    return this[data]['accessToken']
  }

  get refreshToken () {
    return this[data]['refreshToken']
  }

  get [data] () {
    return this.session.read(this.key, {})
  }
}