import React from 'react'
import theme from './Dropdown.module.scss'

class Dropdown extends React.Component {

  state = {
    isOpen: false
  }

  get className () {
    return this.state.isOpen ? `${theme.default} ${theme.open}` : theme.default
  }

  onToggle = () => {
    this.setState({isOpen: !this.state.isOpen})
  }

  render () {
    const {label, items, onChange} = this.props

    return (
      <div className={this.className}>
        <button onClick={this.onToggle}>{label}</button>
        <div className={theme.list}>
          {items.map(item => <div
              key={item.value}
              className={theme.item}
              onClick={() => {
                this.onToggle()
                onChange && onChange(item.value)
              }}
            >{item.label}</div>
          )}
        </div>
      </div>
    )
  }
}

export default Dropdown