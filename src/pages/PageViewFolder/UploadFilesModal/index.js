import React from 'react'
import { connect } from 'react-redux'
import theme from './UploadFilesModal.module.scss'
import Modal from '../../../components/Modal'
import Controller from '../Controller'
import { inject } from '@nrg/react-di'
import TextInput from '../../../components/TextInput'
import SubmitButton from '../../../components/SubmitButton'
import NewFolderForm from '../../../forms/NewFolderForm'
import { ValidationException } from '@nrg/http'
import { withRouter } from 'react-router'

class UploadFilesModal extends React.Component {

  constructor (props) {
    super(props)
    const {form} = props

    this.state = {
      values: form.values,
      errors: form.errors,
      loading: false
    }
  }

  onSubmit = async (event) => {
    event.preventDefault()

    this.setState({errors: {}, loading: true})

    const {form, controller, history} = this.props

    form.values = this.state.values

    if (form.hasErrors) {
      return this.setState({errors: form.errors, loading: false})
    }

    try {
      const directory = await controller.createDirectory(form.values)
      this.setState({loading: false})
      history.push(`/?path=${encodeURIComponent(directory.parent.path.value)}`)
      this.onClose()

    } catch (error) {
      if (error instanceof ValidationException) {
        this.setState({errors: error.details, loading: false})
      } else {
        console.error(error)
      }
    }
  }

  onChange = (event) => {
    const {name, value} = event.target

    this.setState({
      values: {
        ...this.state.values,
        [name]: value
      }
    })
  }

  onClose = () => {
    const {controller, form} = this.props

    form.reset()

    this.setState({
      values: form.values,
      errors: form.errors
    })

    controller.uploadFilesModal(false)
  }

  render () {
    const {values, errors, loading} = this.state
    const {uploadFilesModal} = this.props

    return (
      <Modal
        isOpen={uploadFilesModal}
        onClose={this.onClose}
      >
        <div className={theme.default}>
          <h2>New Folder</h2>
          <div className={theme.form}>
            <form onSubmit={this.onSubmit}>
              <TextInput
                label={'Folder Name'}
                name={'path'}
                value={values.path}
                error={errors.path}
                autoComplete={'off'}
                onChange={this.onChange}
                onBlur={this.onChange}
              />
              <SubmitButton loading={loading}>Create</SubmitButton>
            </form>
          </div>
        </div>
      </Modal>
    )
  }
}

const mapStateToProps = ({uploadFilesModal}) => ({uploadFilesModal})

const dependencies = {
  controller: Controller,
  form: NewFolderForm
}

export default withRouter(inject(connect(mapStateToProps)(UploadFilesModal), dependencies))