import React from 'react'
import theme from './Logo.module.scss'

const Component = (props) => {
  const className = props.highlight ?
    `${theme.default} ${theme.highlight}` : theme.default

  return (
    <svg className={className} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 42" width="50" height="42">
      <path fill="none" stroke="#bbb" strokeLinecap="round" strokeMiterlimit="10" strokeWidth="2"
            d="M13,33A12,12,0,0,1,13,9h.2A10,10,0,0,1,32.31,7.36a7.29,7.29,0,0,1,1.93-.24,7,7,0,0,1,6.82,6.09A10,10,0,0,1,39,33H37">
      </path>
      <line fill="none" stroke="#bbb" strokeLinecap="round" strokeMiterlimit="10" strokeWidth="2" x1="25" y1="41"
            x2="25" y2="21">
      </line>
      <polyline fill="none" stroke="#bbb" strokeLinecap="round" strokeMiterlimit="10" strokeWidth="2"
                points="31 27 25 21 19 27">
      </polyline>
    </svg>
  )
}

export default Component
