import React from 'react'
import theme from './PasswordInput.module.scss'

class PasswordInput extends React.Component {

  render () {
    const {name, label, value, error, ...rest} = this.props

    return (
      <div className={theme.default}>
        <input
          type={'password'}
          name={name}
          value={value}
          placeholder={label}
          {...rest}
        />
        <div className={theme.error}>{error}</div>
      </div>
    )
  }
}

export default PasswordInput