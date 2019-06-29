import React from 'react'
import theme from './AppWrapper.module.scss'

class AppWrapper extends React.Component {

  render () {
    const {children} = this.props

    return (
      <div className={theme.default}>
        {children}
      </div>
    )
  }
}

export default AppWrapper
