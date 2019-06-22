import { Value } from '@nrg/core'
import { createStore } from 'redux'
import { composeWithDevTools } from 'redux-devtools-extension'

const LOAD_CONFIG = 'LOAD_CONFIG'
const ADD_FILE = 'ADD_FILE'
const SUCCESS_UPLOAD = 'SUCCESS_UPLOAD'
const REMOVE_FILE = 'REMOVE_FILE'
const CLEAR_FILE_LIST = 'CLEAR_FILE_LIST'

const store = Symbol()

export default class extends Value {

  static get services () {
    return {
      client: 'client'
    }
  }

  initState = {
    config: {},
    files: [],
    status: {
      total: 0,
      completed: 0
    }
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
    this.store.dispatch({
      type,
      state: {...this.state, ...diff}
    })
  }

  async loadConfig () {
    const config = await this.client.fetchConfig()
    this.runAction(LOAD_CONFIG, {config})
  }

  addFile (file) {
    const total = this.state.status.total + 1

    this.runAction(ADD_FILE, {
      files: [file, ...this.state.files],
      status: {...this.state.status, total}
    })
  }

  successUpload () {
    const completed = this.state.status.completed + 1

    this.runAction(SUCCESS_UPLOAD, {
      status: {...this.state.status, completed}
    })
  }

  removeFile (file, hasUploaded = true) {
    const total = this.state.status.total - 1
    let completed = this.state.status.completed

    if (hasUploaded) {
      completed--
    }

    this.runAction(REMOVE_FILE, {
      files: this.state.files.filter(item => (item !== file)),
      status: {...this.state.status, total, completed}
    })
  }

  clearFileList () {
    this.runAction(CLEAR_FILE_LIST, {
      files: [],
      status: {total: 0, completed: 0}
    })
  }

  openFile (fileName) {
    this.client.openFile(fileName)
  }
}