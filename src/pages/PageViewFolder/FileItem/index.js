import React from 'react'
import theme from './FileItem.module.scss'
import Controller from '../Controller'
import { inject } from '@nrg/react-di'

const Component = class extends React.Component {

  constructor (props) {
    super(props)
    this.controller = this.props.controller
  }

  onDelete = () => {
    this.controller.deleteFile(this.props.file)
  }

  onView = () => {
    this.controller.openFile(this.props.file)
  }

  onDownload = () => {
    this.controller.downloadFile(this.props.file)
  }

  render () {
    const {file} = this.props
    const name = file.path.fileName.value

    return (
      <li className={`${theme.default} ${theme.success}`}>
        <div className={theme.inner}>
          <div className={theme.progress} style={{width: `${this.percent}%`}}/>
          <div className={theme.name} title={name}>{name}</div>
          <div className={theme.size}>{file.size.toHumanString()}</div>
          <div className={`${theme.controls} ${theme.full}`}>
            <button className={theme.btnView} title="View" onClick={this.onView}>
              <i className="nrg-view"/>
            </button>
            <button className={theme.btnDownload} title="Download" onClick={this.onDownload}>
              <i className="nrg-download"/>
            </button>
            <button className={theme.btnClose} title="Cancel" onClick={this.onDelete}>
              <i className="nrg-across"/>
            </button>
          </div>
        </div>
      </li>
    )
  }
}

const dependencies = {controller: Controller}

export default inject(Component, dependencies)