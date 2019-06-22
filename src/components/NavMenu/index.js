import React from 'react'
import { withRouter } from 'react-router-dom'
import theme from './NavMenu.module.scss'
import { inject } from '@nrg/react-di'
import NavLink from './NavLink'

const Component = class extends React.Component {

  logout = () => {
    const {authControl, history} = this.props

    authControl.logout()
    history.push('/')
  }

  render () {
    const {authControl} = this.props

    if (authControl.isGuest) {
      return (
        <div className={theme.default}>
          <NavLink to="/login">Login</NavLink>
        </div>
      )
    }

    return (
      <div className={theme.default}>
        <NavLink to="/" activeExactOnly={true}>Uploader</NavLink>
        <NavLink to="/view-folder">View</NavLink>
        {
          authControl.isGuest ?
            <NavLink to="/login">Login</NavLink> :
            authControl.authorization && <button onClick={this.logout}>Logout</button>
        }
      </div>
    )
  }
}

const dependencies = {authControl: 'authControl'}

export default withRouter(inject(Component, dependencies))