import React from 'react'
import theme from './AjaxLoading.module.scss'

const Component = class extends React.Component {

  render () {
    return (
      <div className={theme.default}>
        <svg xmlns="http://www.w3.org/2000/svg" width="75px" height="75px" viewBox="0 0 100 100"
             preserveAspectRatio="xMidYMid">
          <circle cx="50" cy="50" r="25" fill="none" stroke="#4d88ff" strokeWidth="2">
            <animate attributeName="r" calcMode="spline" values="0;40" keyTimes="0;1" dur="1" keySplines="0 0.2 0.8 1"
                     begin="-.5s" repeatCount="indefinite"/>
            <animate attributeName="opacity" calcMode="spline" values="1;0" keyTimes="0;1" dur="1"
                     keySplines="0.2 0 0.8 1" begin="-.5s" repeatCount="indefinite"/>
          </circle>
          <circle cx="50" cy="50" r="15" fill="none" stroke="#4d88ff" strokeWidth="2">
            <animate attributeName="r" calcMode="spline" values="0;40" keyTimes="0;1" dur="1" keySplines="0 0.2 0.8 1"
                     begin="0s" repeatCount="indefinite"/>
            <animate attributeName="opacity" calcMode="spline" values="1;0" keyTimes="0;1" dur="1"
                     keySplines="0.2 0 0.8 1" begin="0s" repeatCount="indefinite"/>
          </circle>
          <circle cx="50" cy="50" r="1" fill="#4d88ff">
            <animate attributeName="r" calcMode="spline" values="0;40" keyTimes="0;1" dur="1" keySplines="0 0.2 0.8 1"
                     begin=".5s" repeatCount="indefinite"/>
            <animate attributeName="opacity" calcMode="spline" values="1;0" keyTimes="0;1" dur="1"
                     keySplines="0.2 0 0.8 1" begin=".5s" repeatCount="indefinite"/>
          </circle>
        </svg>
        <span>Loading...</span>
      </div>
    )
  }
}

export default Component
