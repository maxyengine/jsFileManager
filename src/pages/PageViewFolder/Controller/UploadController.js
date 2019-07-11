import uuidv4 from 'uuid/v4'
import { ValidationException } from '@nrg/http'
import FileElement from '../../../forms/elements/FileElement'
import {
  ACTION_START_UPLOAD_FILE,
  ACTION_PROGRESS_FILE_UPLOAD,
  ACTION_SUCCESS_FILE_UPLOAD,
  ACTION_UPLOAD_FILES_MODAL,
  ACTION_COMPLETE_FILE_UPLOAD,
  ACTION_REMOVE_UPLOAD_FILE,
  ACTION_CLEAR_UPLOAD_LIST,
  ACTION_RETRY_UPLOAD_FILE,
  UPLOAD_STATUS_ERROR,
  UPLOAD_STATUS_FATAL,
  UPLOAD_STATUS_PROGRESS,
  UPLOAD_STATUS_START,
  UPLOAD_STATUS_SUCCESS,
  UPLOAD_STATUS_COMPETE
} from '../constants'
import TraitController from './TraitController'

export default class UploadController extends TraitController {

  static get services () {
    return {
      client: 'client',
      fileFactory: 'fileFactory'
    }
  }

  get assignments () {
    return [
      'uploadFilesModal',
      'uploadFiles',
      'removeUploadFile',
      'retryUploadFile',
      'clearUploadList'
    ]
  }

  uploadFilesModal (isOpen) {
    this.action(ACTION_UPLOAD_FILES_MODAL, {uploadFilesModal: isOpen})
  }

  uploadFiles (files) {
    const {config, directory} = this.state

    for (const file of files) {
      const {uploadList} = this.state
      const uploadItem = {
        status: UPLOAD_STATUS_START,
        key: uuidv4(),
        fileName: file.name,
        loaded: 0,
        errorMessage: null,
        uploader: this.client.createFileUploader({}, {path: directory.path.value}),
        file
      }

      this.action(ACTION_START_UPLOAD_FILE, {
        uploadList: [uploadItem, ...uploadList],
      })

      const element = new FileElement({config, value: file})

      if (element.hasError) {
        this.errorFileUpload(uploadItem.key, new ValidationException({
          details: {file: element.error}
        }))
        continue
      }

      this.startUploading(uploadItem.key)
    }
  }

  retryUploadFile (key) {
    const {directory} = this.state
    const uploader = this.client.createFileUploader({}, {path: directory.path.value})

    this.action(ACTION_RETRY_UPLOAD_FILE, {
      uploadList: this.state.uploadList.map(item => item.key !== key ? item : {
        ...item,
        status: UPLOAD_STATUS_START,
        loaded: 0,
        errorMessage: null,
        uploader
      })
    })

    this.startUploading(key)
  }

  removeUploadFile (key) {
    this.action(ACTION_REMOVE_UPLOAD_FILE, {
      uploadList: this.state.uploadList.filter(item => {
        if (UPLOAD_STATUS_PROGRESS === item.status) {
          item.uploader.abort()
        }

        return item.key !== key
      })
    })
  }

  clearUploadList () {
    this.action(ACTION_CLEAR_UPLOAD_LIST, {
      uploadList: []
    })
  }

  startUploading (key) {
    const {uploader, file} = this.state.uploadList.find(item => item.key === key)

    uploader.on('progress', ({loaded}) => {
      this.progressFileUpload(key, loaded > file.size ? file.size : loaded)
    })

    uploader.upload(file)
      .then(raw => {
        this.successFileUpload(key, this.fileFactory.createFile(raw))
      })
      .catch(error => {
        this.errorFileUpload(key, error)
      })
  }

  progressFileUpload (key, loaded) {
    this.action(ACTION_PROGRESS_FILE_UPLOAD, {
      uploadList: this.state.uploadList.map(item => item.key !== key ? item : {
        ...item,
        status: UPLOAD_STATUS_PROGRESS,
        loaded
      })
    })
  }

  successFileUpload (key, uploadedFile) {
    this.action(ACTION_SUCCESS_FILE_UPLOAD, {
      uploadList: this.state.uploadList.map(item => item.key !== key ? item : {
        ...item,
        status: UPLOAD_STATUS_SUCCESS,
        fileName: uploadedFile.path.fileName.value,
        uploadedFile
      })
    })

    setTimeout(() => this.completeFileUpload(key), 2000)
  }

  completeFileUpload (key) {
    this.action(ACTION_COMPLETE_FILE_UPLOAD, {
      uploadList: this.state.uploadList.map(item => item.key !== key ? item : {
        ...item,
        status: UPLOAD_STATUS_COMPETE,
      })
    })
  }

  errorFileUpload (key, error) {
    this.action(ACTION_SUCCESS_FILE_UPLOAD, {
      uploadList: this.state.uploadList.map(item => item.key !== key ? item : {
        ...item,
        status: error instanceof ValidationException ? UPLOAD_STATUS_FATAL : UPLOAD_STATUS_ERROR,
        errorMessage: error instanceof ValidationException ? error.details.file : error.reasonPhrase
      })
    })
  }
}
