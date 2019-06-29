import { Size, Path } from '../values'

const path = Symbol()
const size = Symbol()
const lastModified = Symbol()

class File {

  constructor (raw, isDir = false) {
    this[path] = new Path(raw.path, isDir)
    this[size] = raw.size && new Size(raw.size)
    this[lastModified] = raw.lastModified && new Date(raw.lastModified)
  }

  get path () {
    return this[path]
  }

  get size () {
    return this[size]
  }

  get lastModified () {
    return this[lastModified]
  }
}

export default File