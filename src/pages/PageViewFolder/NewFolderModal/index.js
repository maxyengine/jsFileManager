import React from 'react'
import { connect } from 'react-redux'
import theme from './NewFolderModal.module.scss'
import Modal from '../../../components/Modal'
import Controller from '../Controller'
import { inject } from '@nrg/react-di'
import TextInput from '../../../components/TextInput'
import SubmitButton from '../../../components/SubmitButton'
import NewFolderForm from '../../../forms/NewFolderForm'
import { ValidationException } from '@nrg/http'

class NewFolderModal extends React.Component {

  constructor (props) {
    super(props)
    const {form} = props

    this.state = {
      values: form.values,
      errors: form.errors
    }
  }

  onSubmit = async (event) => {
    event.preventDefault()
    const {form} = this.props

    form.values = this.state.values

    if (form.hasErrors) {
      return this.setState({errors: form.errors})
    }

    try {
      this.authControl.login(
        await this.client.fetchLogin(form.values)
      )
      form.reset()
      this.setState({
        values: form.values,
        errors: form.errors
      })

    } catch (error) {
      if (error instanceof ValidationException) {
        this.setState({errors: error.details})
      } else {

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
    const {controller} = this.props
    controller.newFolderModal(false)
  }

  render () {
    const {values, errors} = this.state
    const {newFolderModal} = this.props

    return (
      <Modal
        isOpen={newFolderModal}
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
                autoComplete={null}
                onChange={this.onChange}
                onBlur={this.onChange}
              />
              <SubmitButton>Create</SubmitButton>
            </form>
          </div>
        </div>
      </Modal>
    )
  }
}

const mapStateToProps = ({newFolderModal}) => ({newFolderModal})

const dependencies = {
  controller: Controller,
  form: NewFolderForm
}

export default inject(connect(mapStateToProps)(NewFolderModal), dependencies)