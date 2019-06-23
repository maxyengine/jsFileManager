import { Value } from '@nrg/core'
import { createStore } from 'redux'
import { composeWithDevTools } from 'redux-devtools-extension'

const FETCH_UPLOADS_FOLDER = 'FETCH_UPLOADS_FOLDER'
const DELETE_FILE = 'REMOVE_FILE'
const SEARCH_FILES = 'SEARCH_FILES'

const store = Symbol()

export default class extends Value {

  static get services () {
    return {
      client: 'client'
    }
  }

  initState = {
    directory: null,
    files: [],
    filteredFiles: [],
    keywords: ''
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

  runAction (type, diff) {
    this.store.dispatch({type, state: {...this.state, ...diff}})
  }

  async fetchUploadsFolder () {
    const directory = await this.client.fetchUploadsFolder()

    const files = directory.isEmpty ? [] : directory.children

    this.runAction(FETCH_UPLOADS_FOLDER, {directory, files, keywords: ''})
  }

  deleteFile (file) {
    try {
      this.client.deleteFile(file.path.value)
      let {files, keywords} = this.state
      files = files.filter(item => (item !== file))

      this.runAction(DELETE_FILE, {files, filteredFiles: this.filterFiles(files, keywords)})
    } catch (e) {
      //todo: handle error
    }
  }

  search (keywords) {
    keywords = keywords.toLocaleLowerCase()
    const {files} = this.state

    this.runAction(SEARCH_FILES, {
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