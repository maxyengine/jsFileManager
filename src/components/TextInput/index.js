import React from 'react'
import theme from './TextInput.module.scss'

class TextInput extends React.Component {

  render () {
    const {name, label, value, error, ...rest} = this.props

    return (
      <div className={theme.default}>
        <input
          type={'text'}
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

export default TextInput