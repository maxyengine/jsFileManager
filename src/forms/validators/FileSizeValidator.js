const {Value} = require('@nrg/core')
const {Message} = require('@nrg/i18n')
const fileSize = require('filesize')

const maxSize = Symbol()

export default class extends Value {

  get defaults () {
    return {
      errorMaxSize: 'the file size should not exceed %s'
    }
  }

  set maxSize (value) {
    this[maxSize] = value
  }

  isValid (file) {
    this.errorMessage = new Message(this.errorMaxSize, fileSize(this[maxSize]))

    return !this[maxSize] || file.size <= this[maxSize]
  }

  isValidMaxSize (fileSize) {

  }
}
