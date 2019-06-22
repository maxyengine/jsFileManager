import React from 'react'
import { connect } from 'react-redux'
import { inject } from '@nrg/react-di'
import Controller from '../Controller'
import theme from './FileItem.module.scss'
import { ValidationException } from '@nrg/http'
import fileSize from 'filesize'
import FileElement from './FileElement'

const STATUS_PROGRESS = 0
const STATUS_SUCCESS = 1
const STATUS_FATAL = 2
const STATUS_ERROR = 3

const Component = class extends React.Component {

  state = {
    loaded: 0,
    status: STATUS_PROGRESS,
    errorMessage: '',
    fileName: null
  }

  get className () {
    return {
      [STATUS_PROGRESS]: `${theme.default} ${theme.loading}`,
      [STATUS_SUCCESS]: `${theme.default} ${theme.success}`,
      [STATUS_ERROR]: `${theme.default} ${theme.error}`,
      [STATUS_FATAL]: `${theme.default} ${theme.error} ${theme.fatal}`,
    }[this.state.status]
  }

  get percent () {
    const {file} = this.props
    const {loaded} = this.state

    return loaded <= file.size ? Math.ceil(100 * loaded / file.size) : null
  }

  constructor (props) {
    super(props)
    this.client = this.props.client
    this.controller = this.props.controller
    this.fileFactory = this.props.fileFactory
  }

  componentDidMount () {
    const {file: value, config} = this.props
    const element = new FileElement({config, value})

    if (element.hasError) {
      return this.setState({
        status: STATUS_FATAL,
        errorMessage: element.error
      })
    }

    this.uploadFile()
  }

  componentWillUnmount () {
    if (this.uploader && STATUS_SUCCESS !== this.state.status) {
      this.uploader.abort()
    }
  }

  async uploadFile () {
    const {file} = this.props
    const uploader = this.client.createFileUploader()
    this.uploader = uploader

    uploader.on('progress', ({loaded}) => {
      if (loaded > file.size) {
        loaded = file.size
      }
      this.setState({loaded})
    })

    try {
      const raw = await uploader.upload(file)
      const entity = this.fileFactory.createFile(raw)
      const fileName = entity.path.fileName.value

      this.setState({status: STATUS_SUCCESS, fileName}, () => this.controller.successUpload())
    } catch (error) {
      return this.setState({
        status: error instanceof ValidationException ? STATUS_FATAL : STATUS_ERROR,
        errorMessage: error instanceof ValidationException ? error.details.file : error.reasonPhrase
      })
    }
  }

  onClose = () => {
    this.controller.removeFile(this.props.file, STATUS_SUCCESS === this.state.status)
  }

  onView = () => {
    this.controller.openFile(this.state.fileName)
  }

  onRetry = () => {
    this.setState({status: STATUS_PROGRESS}, () => this.uploadFile())
  }

  render () {
    const {file} = this.props
    const {loaded, errorMessage} = this.state
    const fileName = this.state.fileName || file.name

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

const mapStateToProps = ({config}) => ({config})
const dependencies = {
  controller: Controller,
  client: 'client',
  fileFactory: 'fileFactory'
}

export default inject(connect(mapStateToProps)(Component), dependencies)