import {
  ACTION_FETCH_DIRECTORY,
  ACTION_SEARCH_FILES,
  ACTION_NEW_FOLDER_MODAL
} from '../constants'
import TraitController from './TraitController'

export default class DirectoryController extends TraitController {

  static get services () {
    return {
      client: 'client',
    }
  }

  get assignments () {
    return [
      'fetchDirectory',
      'newFolderModal',
      'createDirectory',
      'search',
    ]
  }

  async fetchDirectory (path) {
    this.action(ACTION_FETCH_DIRECTORY, {directory: null, keywords: ''})
    const directory = await this.client.fetchDirectory(path)
    this.action(ACTION_FETCH_DIRECTORY, {directory})
  }

  newFolderModal (isOpen) {
    this.action(ACTION_NEW_FOLDER_MODAL, {newFolderModal: isOpen})
  }

  async createDirectory ({path}) {
    const {directory} = this.state
    const newPath = directory.path.join(path, true)

    return await this.client.createDirectory(newPath.value)
  }

  search (keywords) {
    const {directory} = this.state
    const files = directory.isEmpty ? [] : directory.children
    keywords = keywords.toLocaleLowerCase()

    this.action(ACTION_SEARCH_FILES, {
      keywords,
      filteredFiles: '' === keywords ? files : this.filterFiles(files, keywords)
    })
  }

  filterFiles (files, keywords) {
    return '' === keywords ?
      files :
      files.filter(file => (file.path.fileName.value.toLocaleLowerCase().includes(keywords)))
  }
}