const {Value} = require('@nrg/core')
const {Message} = require('@nrg/i18n')
const fileExtension = require('file-extension')

const allowExtensions = Symbol()
const denyExtensions = Symbol()

export default class extends Value {

  get defaults () {
    return {
      errorExtension: 'the file type is not allowed'
    }
  }

  set allowExtensions (value) {
    this[allowExtensions] = value
  }

  set denyExtensions (value) {
    this[denyExtensions] = value
  }

  isValid (file) {
    this.errorMessage = new Message(this.errorExtension)

    const extension = fileExtension(file.name)

    if (this[denyExtensions]) {
      return !this[denyExtensions].includes(extension)
    }

    if (this[allowExtensions]) {
      return this[allowExtensions].includes(extension)
    }

    return true
  }
}
