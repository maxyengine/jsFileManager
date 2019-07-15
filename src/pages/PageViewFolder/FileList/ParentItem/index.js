import React from 'react'
import theme from './ParentItem.module.scss'
import { Link } from 'react-router-dom'
import { MdReply } from 'react-icons/md'

class ParentItem extends React.Component {

  render () {
    const {file, backRef, isActive, onClick} = this.props
    const path = file.path.value
    const baseName = '..'
    const className = [theme.default]

    if (isActive) {
      className.push(theme.active)
    }

    return (
      <tr className={className.join(' ')} ref={ref => backRef && backRef(ref)} onClick={onClick}>
        <td>
          <div className={theme.iconWrapper}>
            <Link to={{pathname: '/', search: `?path=${path}`}}>
              <MdReply/>
            </Link>
          </div>
        </td>
        <td colSpan={4}>
          <div>
            <Link to={{pathname: '/', search: `?path=${path}`}}>{baseName}</Link>
          </div>
        </td>
      </tr>
    )
  }
}

export default ParentItem