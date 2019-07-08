import { ACTION_DELETE_FILE } from '../constants'
import TraitController from './TraitController'

export default class FileController extends TraitController {

  static get services () {
    return {
      client: 'client'
    }
  }

  get assignments () {
    return [
      'deleteFile',
      'openFile',
      'downloadFile'
    ]
  }

  async deleteFile (file) {
    try {
      this.client.deleteFile(file.path.value)

      const {keywords} = this.state
      const directory = await this.client.fetchDirectory(file.parent.path.value)

      this.action(ACTION_DELETE_FILE, {
        directory,
        filteredFiles: directory.isEmpty ? [] : this.filterFiles(directory.children, keywords)
      })

    } catch (e) {
      //todo: handle error
    }
  }

  openFile (file) {
    this.client.openFile(file.path.fileName.value)
  }

  downloadFile (file) {
    this.client.downloadFile(file.path.fileName.value)
  }
}