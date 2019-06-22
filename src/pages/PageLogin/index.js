import React from 'react'
import { inject } from '@nrg/react-di'
import theme from './Login.module.scss'
import UserLoginForm from '../../forms/UserLoginForm'
import TextInput from './TextInput'
import PasswordInput from './PasswordInput'
import Page from '../../components/Page'
import { Redirect } from 'react-router-dom'
import { ValidationException } from '@nrg/http'

const Component = class extends React.Component {

  constructor (props) {
    super(props)

    this.form = props.form
    this.client = props.client
    this.authControl = props.authControl

    this.state = {
      values: this.form.values,
      errors: this.form.errors
    }
  }

  onSubmit = async (event) => {
    event.preventDefault()

    this.form.values = this.state.values

    if (this.form.hasErrors) {
      return this.setState({errors: this.form.errors})
    }

    try {
      this.authControl.login(
        await this.client.fetchLogin(this.form.values)
      )
      this.form.reset()
      this.setState({
        values: this.form.values,
        errors: this.form.errors
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

  render () {
    const {values, errors} = this.state
    const {from} = this.props.location.state || {from: {pathname: '/'}}

    if (!this.authControl.isGuest) {
      return <Redirect to={from}/>
    }

    return (
      <Page isReady={true}>
        <div className={theme.default}>
          <form onSubmit={this.onSubmit}>
            <TextInput
              label={'Email'}
              name={'email'}
              value={values.email}
              error={errors.email}
              autoComplete={null}
              onChange={this.onChange}
              onBlur={this.onChange}
            />
            <PasswordInput
              label={'Password'}
              name={'password'}
              value={values.password}
              error={errors.password}
              autoComplete={'password'}
              onChange={this.onChange}
              onBlur={this.onChange}
            />
            <button>Login</button>
          </form>
        </div>
      </Page>
    )
  }
}

const
  dependencies = {
    client: 'client',
    authControl: 'authControl',
    form: UserLoginForm
  }

export default inject(Component, dependencies)
