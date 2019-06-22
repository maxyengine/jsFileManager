import React from 'react'

export default ({name, label, value, error, ...rest}) => {
  return (
    <div>
      <input
        type={'password'}
        name={name}
        value={value}
        placeholder={label}
        {...rest}
      />
      <div>{error}</div>
    </div>
  )
}
