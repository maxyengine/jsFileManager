import React from 'react'
import theme from './ParentItem.module.scss'
import { Link } from 'react-router-dom'
import { MdReply } from 'react-icons/md'

class ParentItem extends React.Component {

  render () {
    const {parent} = this.props
    const path = parent.path.value
    const baseName = '..'

    return (
      <tr className={theme.default}>
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