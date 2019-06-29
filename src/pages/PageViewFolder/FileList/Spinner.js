import React from 'react'
import theme from './Spinner.module.scss'

class Spinner extends React.Component {

  render () {
    return (
      <div className={theme.default}>
        <div className={theme.bounce1}/>
        <div className={theme.bounce2}/>
        <div className={theme.bounce3}/>
      </div>
    )
  }
}

export default Spinner
