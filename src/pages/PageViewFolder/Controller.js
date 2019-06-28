import { Value } from '@nrg/core'
import { createStore } from 'redux'
import { composeWithDevTools } from 'redux-devtools-extension'

const FETCH_DIRECTORY = 'FETCH_DIRECTORY'
const DELETE_FILE = 'REMOVE_FILE'
const SEARCH_FILES = 'SEARCH_FILES'
const NEW_FOLDER_MODAL = 'NEW_FOLDER_MODAL'

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
    newFolderModal: true
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

  async fetchDirectory (path) {
    const directory = await this.client.fetchDirectory(path)

    this.action(FETCH_DIRECTORY, {directory, keywords: ''})
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