import React from 'react'
import theme from './SubmitButton.module.scss'
import Spinner from './Spinner'

class Button extends React.Component {

  render () {
    const {children, loading} = this.props

    return (
      <button className={theme.default}>
        {loading ? <Spinner/> : children}
      </button>
    )
  }
}

export default Button