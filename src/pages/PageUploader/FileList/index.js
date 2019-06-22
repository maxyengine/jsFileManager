import React from 'react'
import { connect } from 'react-redux'
import { inject } from '@nrg/react-di'
import Controller from '../Controller'
import theme from './FileList.module.scss'
import FileItem from '../FileItem'

const Component = class extends React.Component {

  constructor (props) {
    super(props)
    this.controller = this.props.controller
  }

  componentDidMount () {
    this.controller.clearFileList()
  }

  render () {
    const {files} = this.props

    return (
      <div className={theme.default}>
        <ul>
          {files.map(
            file =>
              <FileItem key={file._id} file={file}/>
          )}
        </ul>
      </div>
    )
  }
}

const mapStateToProps = ({files}) => ({files})
const dependencies = {controller: Controller}

export default inject(connect(mapStateToProps)(Component), dependencies)