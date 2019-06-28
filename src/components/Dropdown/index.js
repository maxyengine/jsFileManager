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

  handleClickOutside = (event) => {
    if (this.wrapperRef && !this.wrapperRef.contains(event.target)) {
      this.state.isOpen && this.setState({isOpen: false})
    }
  }

  componentDidMount () {
    document.addEventListener('mousedown', this.handleClickOutside)
  }

  componentWillUnmount () {
    document.removeEventListener('mousedown', this.handleClickOutside)
  }

  render () {
    const {icon, items} = this.props

    return (
      <div className={this.className} ref={ref => {this.wrapperRef = ref}}>
        <button onClick={this.onToggle}>{icon}</button>
        <div className={theme.list}>
          {items.map(item => <div
              key={item.label}
              className={theme.item}
              onClick={() => {
                this.onToggle()
                item.onClick && item.onClick()
              }}
            >{item.label}</div>
          )}
        </div>
      </div>
    )
  }
}

export default Dropdown