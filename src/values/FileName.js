const path = require('path')
const fileExtension = require('file-extension')

const
  value = Symbol(),
  isDirectory = Symbol(),
  baseName = Symbol(),
  extension = Symbol()

const self = class {

  constructor (val, isDir = false) {
    this[value] = val
    this[isDirectory] = isDir

    if (isDir) {
      this[baseName] = val
    } else {
      this[extension] = fileExtension(val)
      this[baseName] = path.basename(val, path.extname(val))
    }
  }

  get value () {
    return this[value]
  }

  get isDirectory () {
    return this[isDirectory]
  }

  get baseName () {
    return this[baseName]
  }

  get extension () {
    return this[extension]
  }

  get length () {
    return this.value.length
  }

  toString () {
    return this.value
  }

  isEqual (fileName) {
    return fileName instanceof self ? fileName.value === this.value : fileName === this.value
  }
}

export default self