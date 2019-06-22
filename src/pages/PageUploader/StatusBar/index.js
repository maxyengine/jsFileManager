import React from 'react'
import { connect } from 'react-redux'
import { inject } from '@nrg/react-di'
import Controller from '../Controller'
import theme from './StatusBar.module.scss'

const Component = class extends React.Component {

  constructor (props) {
    super(props)
    this.controller = this.props.controller
  }

  onClear = () => {
    this.controller.clearFileList()
  }

  render () {
    const {files, status: {completed, total}} = this.props

    return files.length ? (
      <div className={theme.default}>
        <span>Files Completed: {completed}/{total}</span>
        <button title="Clear all Uploads" onClick={this.onClear}>
          Clear
        </button>
      </div>
    ) : null
  }
}

const mapStateToProps = ({files, status}) => ({files, status})
const dependencies = {controller: Controller}

export default inject(connect(mapStateToProps)(Component), dependencies)