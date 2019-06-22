import React from 'react'
import { Route, Link } from 'react-router-dom'
import theme from './NavMenu.module.scss'

const Component = class extends React.Component {

  render () {
    const {children, to, activeExactOnly} = this.props

    return (
      <Route
        path={to}
        exact={activeExactOnly}
        children={({match}) => (
          <Link className={match ? theme.active : ''} to={to}>
            {children}
          </Link>
        )}
      />
    )
  }
}

export default Component