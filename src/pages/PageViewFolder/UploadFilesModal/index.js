import React from 'react'
import { connect } from 'react-redux'
import theme from './UploadFilesModal.module.scss'
import Modal from '../../../components/Modal'
import Controller from '../Controller'
import { inject } from '@nrg/react-di'
import UploadList from './UploadList'
import { withRouter } from 'react-router'
import StatusBar from './StatusBar'
import DragArea from './DragArea'

class UploadFilesModal extends React.Component {

  onClose = () => {
    const {controller, history, directory} = this.props
    controller.uploadFilesModal(false)
    history.push(`/?path=${encodeURIComponent(directory.path.value)}`)
  }

  render () {
    const {uploadFilesModal} = this.props

    return (
      <Modal
        isOpen={uploadFilesModal}
        onClose={this.onClose}
      >
        <div className={theme.default}>
          <DragArea/>
          <StatusBar/>
          <UploadList/>
        </div>
      </Modal>
    )
  }
}

const mapStateToProps = ({uploadFilesModal, directory}) => ({uploadFilesModal, directory})

const dependencies = {
  controller: Controller
}

export default withRouter(inject(connect(mapStateToProps)(UploadFilesModal), dependencies))