import React from 'react'
import theme from './AjaxError.module.scss'

const Component = class extends React.Component {

  render () {
    const {statusCode, reasonPhrase} = this.props.error

    return (
      <div className={theme.default}>
        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 50 42" width="50" height="42">
          <path fill="none" stroke="#FF4D88" strokeWidth="2" strokeLinecap="round" strokeMiterlimit="10"
                d="M13,33C6.4,33,1,27.6,1,21S6.4,9,13,9h0.2c1.1-5.4,6.4-8.9,11.8-7.8c3.3,0.7,6.1,3,7.3,6.2 c0.6-0.2,1.3-0.2,1.9-0.2c3.5,0.1,6.4,2.7,6.8,6.1c5.4,1.1,8.9,6.4,7.8,11.8c-1,4.6-5.1,8-9.8,8h-2"/>
          <line fill="none" stroke="#FF4D88" strokeWidth="2" strokeLinecap="round" strokeMiterlimit="10" x1="25"
                y1="41" x2="25" y2="31"/>
          <polyline fill="none" stroke="#FF4D88" strokeWidth="2" strokeLinecap="round" strokeMiterlimit="10"
                    points="31,27 25,21 19,27 "/>
          <polyline fill="none" stroke="#FF4D88" strokeWidth="2" strokeLinecap="round" strokeMiterlimit="10"
                    points="19,15 25,21 31,15 "/>
        </svg>
        <span>Error {statusCode}</span>
        <span>{reasonPhrase}</span>
      </div>
    )
  }
}

export default Component
