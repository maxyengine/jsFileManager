import React, { Component } from 'react'
import theme from './Modal.module.scss'
import {MdClose} from 'react-icons/md'

class Modal extends Component {

  render () {
    const {children, isOpen, onOpen, onClose} = this.props

    if (!isOpen) {
      return null
    }

    onOpen && onOpen()

    return (
      <div className={theme.default}>
        <div className={theme.popup}>
          <button className={theme.close} onClick={onClose}>
            <MdClose/>
          </button>
          {children}
        </div>
      </div>
    )
  }
}

export default Modal
