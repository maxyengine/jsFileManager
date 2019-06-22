import { File, Directory } from '../entities'

export default class {

  createFile (raw) {
    return new File({...raw})
  }

  createDirectory (raw) {
    const children = raw.children && raw.children.map(child => this.createEntity(child))

    return new Directory({...raw, children})
  }

  createEntity (raw) {
    switch (raw.type) {
      case 'file':
        return this.createFile(raw)
      case 'directory':
        return this.createDirectory(raw)
      default:
        throw new Error('Unknown file type')
    }
  }
}
