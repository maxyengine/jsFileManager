import React from 'react'
import { connect } from 'react-redux'
import { inject } from '@nrg/react-di'
import Controller from '../../../Controller'
import theme from './FileItem.module.scss'
import fileSize from 'filesize'
import {
  UPLOAD_STATUS_ERROR,
  UPLOAD_STATUS_FATAL,
  UPLOAD_STATUS_PROGRESS,
  UPLOAD_STATUS_START,
  UPLOAD_STATUS_SUCCESS
} from '../../../constants'

class UploadItem extends React.Component {

  get className () {
    const {uploadItem: {status}} = this.props

    return {
      [UPLOAD_STATUS_START]: `${theme.default} ${theme.loading}`,
      [UPLOAD_STATUS_PROGRESS]: `${theme.default} ${theme.loading}`,
      [UPLOAD_STATUS_SUCCESS]: `${theme.default} ${theme.success}`,
      [UPLOAD_STATUS_ERROR]: `${theme.default} ${theme.error}`,
      [UPLOAD_STATUS_FATAL]: `${theme.default} ${theme.error} ${theme.fatal}`,
    }[status]
  }

  get percent () {
    const {uploadItem: {file, loaded}} = this.props

    return loaded <= file.size ? Math.ceil(100 * loaded / file.size) : null
  }

  /*componentDidMount () {
    const {uploadItem: {file, status}, config} = this.props

    const element = new FileElement({config, value: file})

    if (element.hasError) {
      return this.setState({
        status: STATUS_FATAL,
        errorMessage: element.error
      })
    }

    this.uploadFile()
  }*/

  /*componentWillUnmount () {
    const {uploadItem: {status}} = this.props

    if (this.uploader && STATUS_SUCCESS !== status) {
      this.uploader.abort()
    }
  }*/

  /*async uploadFile () {
    const {client, controller, fileFactory, directory, uploadItem: {key, file}} = this.props

    this.uploader = client.createFileUploader()
    this.uploader.bodyParams = {path: directory.path.value}

    this.uploader.on('progress', ({loaded}) => {
      controller.progressFileUpload(key, loaded > file.size ? file.size : loaded)
    })

    try {
      const raw = await this.uploader.upload(file)
      const entity = fileFactory.createFile(raw)
      const fileName = entity.path.fileName.value

      controller.successFileUpload(key, fileName)

    } catch (error) {
      /!*return this.setState({
        status: error instanceof ValidationException ? STATUS_FATAL : STATUS_ERROR,
        errorMessage: error instanceof ValidationException ? error.details.file : error.reasonPhrase
      })*!/
    }
  }*/

  onClose = () => {
    const {controller} = this.props
    controller.removeFile(this.props.file, UPLOAD_STATUS_SUCCESS === this.state.status)
  }

  onView = () => {
    const {controller} = this.props
    controller.openFile(this.state.fileName)
  }

  onRetry = () => {
    //this.setState({status: STATUS_PROGRESS}, () => this.uploadFile())
  }

  render () {
    const {uploadItem: {file, fileName, loaded, errorMessage}} = this.props

    return (
      <li className={this.className}>

        <div className={theme.inner}>
          <div className={theme.progress} style={{width: `${this.percent}%`}}/>
          <div className={theme.name} title={fileName}>{fileName}</div>
          <div className={theme.size}>
            <span>{fileSize(loaded)}</span>{fileSize(file.size)}
          </div>
          <div className={theme.controls}>
            <button className={theme.btnRetry} title="Retry" onClick={this.onRetry}>
              <i className="nrg-retry"/>
            </button>
            <button className={theme.btnView} title="View" onClick={this.onView}>
              <i className="nrg-view"/>
            </button>
            <button className={theme.btnClose} title="Cancel" onClick={this.onClose}>
              <i className="nrg-across"/>
            </button>
          </div>
        </div>
        <div className={theme.errorMessage}>{errorMessage}</div>
      </li>
    )
  }
}

const mapStateToProps = ({config, directory}) => ({config, directory})
const dependencies = {
  controller: Controller,
  client: 'client',
  fileFactory: 'fileFactory'
}

export default inject(connect(mapStateToProps)(UploadItem), dependencies)