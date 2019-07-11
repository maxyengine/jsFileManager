import React from 'react'
import { connect } from 'react-redux'
import theme from './StatusBar.module.scss'
import Controller from '../../Controller'
import { inject } from '@nrg/react-di'
import { UPLOAD_STATUS_COMPETE } from '../../constants'

class StatusBar extends React.Component {

  get completed () {
    const {uploadList} = this.props
    let completed = 0

    uploadList.forEach(item => {
      if (UPLOAD_STATUS_COMPETE === item.status) {
        completed++
      }
    })

    return completed
  }

  onClear = () => {
    const {controller} = this.props

    controller.clearUploadList()
  }

  render () {
    const {uploadList} = this.props
    const total = uploadList.length

    if (!total) {
      return null
    }

    return (
      <div className={theme.default}>
        <span>Files Completed: {this.completed}/{total}</span>
        <button title="Clear all Uploads" onClick={this.onClear}>Clear</button>
      </div>
    )
  }
}

const mapStateToProps = ({uploadList}) => ({uploadList})
const dependencies = {controller: Controller}

export default inject(connect(mapStateToProps)(StatusBar), dependencies)