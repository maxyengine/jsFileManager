import React from 'react'
import theme from './EmpyFolder.module.scss'

export default class extends React.Component {

  render () {
    return (
      <div className={theme.default}>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 42" width="50" height="42">
          <path fill="none" stroke="#ccc" strokeLinecap="round" strokeMiterlimit="10" strokeWidth="2"
            d="M25,6.8h22.1c1.1,0,1.9,0.9,1.9,1.9v5.8 M49,39.1V14.4c0-1.1-0.9-1.9-1.9-1.9H31.6c-0.5,0-1-0.2-1.4-0.6 L19.8,1.6C19.4,1.2,19,1,18.4,1H2.9C1.9,1,1,1.9,1,2.9v36.2C1,40.1,1.9,41,2.9,41h44.2C48.1,41,49,40.1,49,39.1z M13,21h12 M13,26 h24 M13,31h24"/>
        </svg>
        <span>Folder is empty …</span>
      </div>
    )
  }
}
