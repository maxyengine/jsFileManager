import React, { Component } from 'react'
import theme from './Modal.module.scss'
import {FaTimesCircle} from 'react-icons/fa'

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
            <FaTimesCircle/>
          </button>
          {children}
        </div>
      </div>
    )
  }
}

export default Modal
