const fileSize = require('filesize')

const val = Symbol()

export default class {

  constructor (value) {
    this[val] = value
  }

  get value () {
    return this[val]
  }

  toHumanString () {
    return fileSize(this.value)
  }

  toString () {
    return this.toHumanString()
  }
}
