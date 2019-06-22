import React from 'react'
import theme from './Page.module.scss'
import AjaxLoading from '../AjaxLoading'
import AjaxError from '../AjaxError'

export default (props) => {
  const {error, isReady, children} = props
  let content = null

  if (error) {
    content = <AjaxError error={error}/>
  } else if (isReady) {
    content = children
  } else {
    content = <AjaxLoading/>
  }

  return (
    <div className={theme.default}>
      {content}
    </div>
  )
}
