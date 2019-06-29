import { Value } from '@nrg/core'
import { createStore } from 'redux'
import { composeWithDevTools } from 'redux-devtools-extension'

const FETCH_DIRECTORY = 'FETCH_DIRECTORY'
const DELETE_FILE = 'REMOVE_FILE'
const SEARCH_FILES = 'SEARCH_FILES'
const NEW_FOLDER_MODAL = 'NEW_FOLDER_MODAL'
const UPLOAD_FILES_MODAL = 'UPLOAD_FILES_MODAL'

const store = Symbol()

export default class extends Value {

  static get services () {
    return {
      client: 'client'
    }
  }

  initState = {
    directory: null,
    filteredFiles: [],
    keywords: '',
    newFolderModal: false,
    uploadFilesModal: false
  }

  constructor (...args) {
    super(...args)
    this[store] = createStore((state = this.initState, action) => action.state || state, composeWithDevTools())
  }

  get store () {
    return this[store]
  }

  get state () {
    return this.store.getState()
  }

  action (type, diff) {
    this.store.dispatch({type, state: {...this.state, ...diff}})
  }

  newFolderModal (isOpen) {
    this.action(NEW_FOLDER_MODAL, {newFolderModal: isOpen})
  }

  uploadFilesModal (isOpen) {
    this.action(UPLOAD_FILES_MODAL, {uploadFilesModal: isOpen})
  }

  async fetchDirectory (path) {
    this.action(FETCH_DIRECTORY, {directory: null, keywords: ''})
    const directory = await this.client.fetchDirectory(path)
    this.action(FETCH_DIRECTORY, {directory})
  }

  async createDirectory ({path}) {
    const {directory} = this.state
    const newPath = directory.path.join(path, true)

    return await this.client.createDirectory(newPath.value)
  }

  async deleteFile (file) {
    try {
      this.client.deleteFile(file.path.value)

      const {keywords} = this.state
      const directory = await this.client.fetchDirectory(file.parent.path.value)

      this.action(DELETE_FILE, {
        directory,
        filteredFiles: directory.isEmpty ? [] : this.filterFiles(directory.children, keywords)
      })

    } catch (e) {
      //todo: handle error
    }
  }

  search (keywords) {
    const {directory} = this.state
    const files = directory.isEmpty ? [] : directory.children
    keywords = keywords.toLocaleLowerCase()

    this.action(SEARCH_FILES, {
      keywords,
      filteredFiles: '' === keywords ? files : this.filterFiles(files, keywords)
    })
  }

  filterFiles (files, keywords) {
    return '' === keywords ?
      files :
      files.filter(file => (file.path.fileName.value.toLocaleLowerCase().includes(keywords)))
  }

  openFile (file) {
    this.client.openFile(file.path.fileName.value)
  }

  downloadFile (file) {
    this.client.downloadFile(file.path.fileName.value)
  }
}