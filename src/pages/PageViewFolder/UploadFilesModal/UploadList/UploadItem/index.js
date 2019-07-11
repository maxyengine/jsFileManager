import React from 'react'
import { connect } from 'react-redux'
import { inject } from '@nrg/react-di'
import Controller from '../../../Controller'
import theme from './FileItem.module.scss'
import fileSize from 'filesize'
import {
  UPLOAD_STATUS_COMPETE,
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
      [UPLOAD_STATUS_COMPETE]: `${theme.default}`,
      [UPLOAD_STATUS_ERROR]: `${theme.default} ${theme.error}`,
      [UPLOAD_STATUS_FATAL]: `${theme.default} ${theme.error} ${theme.fatal}`,
    }[status]
  }

  get percent () {
    const {uploadItem: {file, loaded}} = this.props

    return loaded <= file.size ? Math.ceil(100 * loaded / file.size) : null
  }

  onClose = () => {
    const {controller, uploadItem: {key}} = this.props
    controller.removeUploadFile(key)
  }

  onView = () => {
    const {controller, uploadItem: {uploadedFile}} = this.props
    controller.openFile(uploadedFile)
  }

  onRetry = () => {
    const {controller, uploadItem: {key}} = this.props
    controller.retryUploadFile(key)
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
const dependencies = {controller: Controller}

export default inject(connect(mapStateToProps)(UploadItem), dependencies)