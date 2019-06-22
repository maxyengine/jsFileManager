import FileName from './FileName'

const
  value = Symbol(),
  isDirectory = Symbol(),
  storageId = Symbol(),
  fileName = Symbol(),
  filePath = Symbol(),
  isRoot = Symbol(),
  parent = Symbol()

const self = class {

  constructor (val, isDir = false) {
    [this[storageId], this[filePath]] = val.split('://')

    const path = this[filePath].split('/')

    this[value] = val
    this[isDirectory] = isDir
    this[fileName] = new FileName(path.pop(), isDir)
    this[isRoot] = '' === this[filePath]
    this[parent] = this[isRoot] ? null : new self(this[storageId] + '://' + path.join('/'), true)
  }

  get value () {
    return this[value]
  }

  get isDirectory () {
    return this[isDirectory]
  }

  get storageId () {
    return this[storageId]
  }

  get fileName () {
    return this[fileName]
  }

  get isRoot () {
    return this[isRoot]
  }

  get parent () {
    return this[parent]
  }

  join (path, isDir = false) {
    if (!this.isDirectory) {
      throw new Error('Path must be directory to join to it')
    }

    return this.value === this.storageId + '://' ?
      new self(this.value + path, isDir) :
      new self(this.value + '/' + path, isDir)
  }

  toString () {
    return this.value
  }

  isEqual (path) {
    return path instanceof self ? path.value === this.value : path === this.value
  }

  contains (path) {
    do {
      if (path.isEqual(this)) {
        return true
      }
      path = path.parent
    } while (path)

    return false
  }
}

export default self