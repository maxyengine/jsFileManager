import { Value } from '@nrg/core'
import cookies from 'browser-cookies'

export default class extends Value {

  get defaults () {
    return {
      name: 'app'
    }
  }

  readAll () {
    return JSON.parse(cookies.get(this.name) || '{}')
  }

  writeAll (data) {
    cookies.set(this.name, JSON.stringify(data))
  }

  read (key, byDefault) {
    const data = this.readAll()

    return data[key] || byDefault
  }

  write (key, value) {
    const data = this.readAll()
    data[key] = value
    this.writeAll(data)
  }

  remove (key) {
    const data = this.readAll()
    delete data[key]
    this.writeAll(data)
  }

  has (key) {
    return !!this.read(key)
  }
}