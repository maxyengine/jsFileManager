import React from 'react'
import theme from './NoMatchedFiles.module.scss'

export default class extends React.Component {

  render () {
    return (
      <div className={theme.default}>
        <span>No matched files …</span>
      </div>
    )
  }
}
