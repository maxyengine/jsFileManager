import React from 'react'
import { connect } from 'react-redux'
import { inject } from '@nrg/react-di'
import Controller from '../Controller'
import theme from './DragArea.module.scss'
import uuidv4 from 'uuid/v4'
import fileSize from 'filesize'
import Logo from '../../../components/Logo'

const STATUS_DEFAULT = 0
const STATUS_DRAG_START = 1

const Component = class extends React.Component {

  state = {
    status: STATUS_DEFAULT,
  }

  constructor (props) {
    super(props)
    this.controller = this.props.controller
  }

  get className () {
    return {
      [STATUS_DEFAULT]: theme.default,
      [STATUS_DRAG_START]: `${theme.default} ${theme.drag}`,
    }[this.state.status]
  }

  onBrowseFiles = () => {
    this.fileInput.click()
  }

  onSelectFiles = (e) => {
    this.onDragFinish(e)

    const {files} = e.dataTransfer || e.target

    for (const file of files) {
      file._id = uuidv4()
      this.controller.addFile(file)
    }

    this.fileInput.value = null
  }

  onDragStart = (e) => {
    e.preventDefault()
    this.setState({status: STATUS_DRAG_START})
  }

  onDragFinish = (e) => {
    e.preventDefault()
    this.setState({status: STATUS_DEFAULT})
  }

  render () {
    const {maxSize} = this.props.config

    return (
      <div className={this.className}
           onDragEnter={this.onDragStart}
           onDragOver={this.onDragStart}
           onDragLeave={this.onDragFinish}
           onDragEnd={this.onDragFinish}
           onDrop={this.onSelectFiles}
      >
        <Logo highlight={this.state.status === STATUS_DRAG_START}/>

        <div className={theme.text}>
          <span>Drop files here to upload</span>
          <span>or</span>
          <span className={theme.browseButton}>
            <span onClick={this.onBrowseFiles}>
              Browse Files
            </span>
            <input type="file" multiple={true} ref={ref => this.fileInput = ref} onChange={this.onSelectFiles}/>
          </span>
          {maxSize && <span>The maximum file size is {fileSize(maxSize)}</span>}
        </div>
      </div>
    )
  }
}

const mapStateToProps = ({config}) => ({config})
const dependencies = {controller: Controller}

export default inject(connect(mapStateToProps)(Component), dependencies)
