import React from 'react'
import { inject } from '@nrg/react-di'
import { Route, Redirect } from 'react-router-dom'

const Component = class extends React.Component {

  render () {
    const {component: Component, authControl, ...rest} = this.props

    return (
      <Route
        {...rest}
        render={props =>
          authControl.isGuest ?
            <Redirect to={{pathname: '/login', state: {from: props.location}}}/> :
            <Component {...props} />
        }
      />
    )
  }
}

const dependencies = {authControl: 'authControl'}

export default inject(Component, dependencies)