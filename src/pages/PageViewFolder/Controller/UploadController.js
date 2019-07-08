import uuidv4 from 'uuid/v4'
import { ValidationException } from '@nrg/http'
import FileElement from '../../../forms/elements/FileElement'
import {
  ACTION_UPLOAD_FILE,
  ACTION_PROGRESS_FILE_UPLOAD,
  ACTION_SUCCESS_FILE_UPLOAD,
  ACTION_UPLOAD_FILES_MODAL,
  UPLOAD_STATUS_ERROR,
  UPLOAD_STATUS_FATAL,
  UPLOAD_STATUS_PROGRESS,
  UPLOAD_STATUS_START,
  UPLOAD_STATUS_SUCCESS
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
      'progressFileUpload',
      'successFileUpload',
      'errorFileUpload',
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
        file: file,
        fileName: file.name,
        loaded: 0,
        errorMessage: null
      }

      this.action(ACTION_UPLOAD_FILE, {
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
    this.action(ACTION_PROGRESS_FILE_UPLOAD, {
      uploadList: this.state.uploadList.map(item => item.key !== key ? item : {
        ...item,
        status: UPLOAD_STATUS_PROGRESS,
        loaded
      })
    })
  }

  successFileUpload (key, file) {
    this.action(ACTION_SUCCESS_FILE_UPLOAD, {
      uploadList: this.state.uploadList.map(item => item.key !== key ? item : {
        ...item,
        status: UPLOAD_STATUS_SUCCESS,
        fileName: file.path.fileName.value,
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

  /*removeFile (file, hasUploaded = true) {
    const total = this.state.status.total - 1
    let completed = this.state.status.completed

    if (hasUploaded) {
      completed--
    }

    this.runAction(ACTION_DELETE_FILE, {
      files: this.state.files.filter(item => (item !== file)),
      status: {...this.state.status, total, completed}
    })
  }*/
}