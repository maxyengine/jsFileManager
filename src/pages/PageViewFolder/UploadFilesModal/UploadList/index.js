import React from 'react'
import { connect } from 'react-redux'
import { inject } from '@nrg/react-di'
import Controller from '../../Controller'
import theme from './UploadList.module.scss'
import UploadItem from './UploadItem'


class UploadList extends React.Component {

  render () {
    const {uploadList} = this.props

    return (
      <div className={theme.default}>
        <ul>
          {uploadList.map(uploadItem => <UploadItem key={uploadItem.key} uploadItem={uploadItem}/>)}
        </ul>
      </div>
    )
  }
}

const mapStateToProps = ({uploadList}) => ({uploadList})
const dependencies = {controller: Controller}

export default inject(connect(mapStateToProps)(UploadList), dependencies)