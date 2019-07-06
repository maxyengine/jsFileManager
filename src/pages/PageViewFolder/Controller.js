import { Value } from '@nrg/core'
import { createStore } from 'redux'
import { composeWithDevTools } from 'redux-devtools-extension'
import uuidv4 from 'uuid/v4'
import { ValidationException } from '@nrg/http'
import FileElement from '../../forms/elements/FileElement'
import { STATUS_ERROR, STATUS_FATAL, STATUS_PROGRESS, STATUS_START, STATUS_SUCCESS } from './uploadItemsStatuses'

const LOAD_CONFIG = 'LOAD_CONFIG'

const FETCH_DIRECTORY = 'FETCH_DIRECTORY'
const DELETE_FILE = 'REMOVE_FILE'
const SEARCH_FILES = 'SEARCH_FILES'

const UPLOAD_FILE = 'UPLOAD_FILE'
const PROGRESS_FILE_UPLOAD = 'PROGRESS_FILE_UPLOAD'
const SUCCESS_FILE_UPLOAD = 'SUCCESS_FILE_UPLOAD'

const NEW_FOLDER_MODAL = 'NEW_FOLDER_MODAL'
const UPLOAD_FILES_MODAL = 'UPLOAD_FILES_MODAL'

const store = Symbol()

export default class extends Value {

  static get services () {
    return {
      client: 'client',
      fileFactory: 'fileFactory'
    }
  }

  initState = {
    config: {},
    directory: null,
    keywords: '',
    filteredFiles: [],
    uploadList: [],
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

  async loadConfig () {
    const config = await this.client.fetchConfig()
    this.action(LOAD_CONFIG, {config})
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

  uploadFiles (files) {
    const {config, directory} = this.state

    for (const file of files) {
      const {uploadList} = this.state

      const uploadItem = {
        status: STATUS_START,
        key: uuidv4(),
        file: file,
        fileName: file.name,
        loaded: 0,
        errorMessage: null
      }

      this.action(UPLOAD_FILE, {
        uploadList: [uploadItem, ...uploadList],
      })

      const element = new FileElement({config, value: file})

      if (element.hasError) {
        this.errorFileUpload(uploadItem.key, new ValidationException({
          details: {file: element.error}
        }))
        continue
      }

      const uploader = this.client.createFileUploader({}, {path: directory.path.value})

      uploader.on('progress', ({loaded}) => {
        this.progressFileUpload(uploadItem.key, loaded > file.size ? file.size : loaded)
      })

      uploader.upload(file)
        .then(raw => {
          this.successFileUpload(uploadItem.key, this.fileFactory.createFile(raw))
        })
        .catch(error => {
          this.errorFileUpload(uploadItem.key, error)
        })
    }
  }

  progressFileUpload (key, loaded) {
    this.action(PROGRESS_FILE_UPLOAD, {
      uploadList: this.state.uploadList.map(item => {
        return item.key !== key ? item : {
          ...item,
          status: STATUS_PROGRESS,
          loaded,
        }
      })
    })
  }

  successFileUpload (key, file) {
    this.action(SUCCESS_FILE_UPLOAD, {
      uploadList: this.state.uploadList.map(item => {
        return item.key !== key ? item : {
          ...item,
          status: STATUS_SUCCESS,
          fileName: file.path.fileName.value,
        }
      })
    })
  }

  errorFileUpload (key, error) {
    this.action(SUCCESS_FILE_UPLOAD, {
      uploadList: this.state.uploadList.map(item => {
        return item.key !== key ? item : {
          ...item,
          status: error instanceof ValidationException ? STATUS_FATAL : STATUS_ERROR,
          errorMessage: error instanceof ValidationException ? error.details.file : error.reasonPhrase
        }
      })
    })
  }

  /*removeFile (file, hasUploaded = true) {
    const total = this.state.status.total - 1
    let completed = this.state.status.completed

    if (hasUploaded) {
      completed--
    }

    this.runAction(REMOVE_FILE, {
      files: this.state.files.filter(item => (item !== file)),
      status: {...this.state.status, total, completed}
    })
  }*/

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