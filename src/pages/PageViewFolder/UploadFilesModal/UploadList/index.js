import React from 'react'
import { connect } from 'react-redux'
import { inject } from '@nrg/react-di'
import Controller from '../../Controller'
import theme from './UploadList.module.scss'
import UploadItem from './UploadItem'

class UploadList extends React.Component {

  render () {
    const {uploadFiles} = this.props

    return (
      <div className={theme.default}>
        <ul>
          {uploadFiles.map(file => <UploadItem key={file._id} file={file}/>)}
        </ul>
      </div>
    )
  }
}

const mapStateToProps = ({uploadFiles}) => ({uploadFiles})
const dependencies = {controller: Controller}

export default inject(connect(mapStateToProps)(UploadList), dependencies)