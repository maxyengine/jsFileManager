import File from './File'

const parent = Symbol()
const children = Symbol()

class Directory extends File {

  constructor (raw) {
    super(raw)

    this[children] = raw.children && [...raw.children]

    if (!this.isRoot) {
      this[parent] = new Directory({path: this.path.parent.value})
    }
  }

  get parent () {
    return this[parent]
  }

  get children () {
    return this[children]
  }

  get isRoot () {
    return this.path.isRoot
  }

  get isEmpty () {
    return !this.children || !this.children.length
  }
}

export default Directory
