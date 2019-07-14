import React from 'react'
import theme from './FileItem.module.scss'
import Controller from '../../Controller'
import { inject } from '@nrg/react-di'
import { icon } from './helpers'
import Directory from '../../../../entities/Directory'
import { Link } from 'react-router-dom'

class FileItem extends React.Component {

  onView = () => {
    const {controller, file} = this.props
    controller.openFile(file)
  }

  render () {
    const {file, backRef, backColumnRef, isActive} = this.props
    const path = file.path.value
    const baseName = file.path.fileName.baseName
    const extension = file instanceof Directory ? 'dir' : file.path.fileName.extension
    const size = file.size && file.size.toHumanString()
    const lastModified = file.lastModified && file.lastModified.toLocaleString()
    const className = [theme.default]
    if (isActive) {
      className.push(theme.active)
    }

    return (
      <tr className={className.join(' ')} ref={ref => backRef && backRef(ref)}>
        <td>
          <div className={theme.iconWrapper} ref={ref => backColumnRef && backColumnRef(ref, 0)}>{icon(file)}</div>
        </td>
        <td>
          <div ref={ref => backColumnRef && backColumnRef(ref, 1)}>
            {
              file instanceof Directory ?
                <Link to={{pathname: '/', search: `?path=${encodeURIComponent(path)}`}}>{baseName}</Link> :
                <span className={theme.baseName} onClick={this.onView}>{baseName}</span>
            }
          </div>
        </td>
        <td>
          <div ref={ref => backColumnRef && backColumnRef(ref, 2)}>{extension}</div>
        </td>
        <td>
          <div ref={ref => backColumnRef && backColumnRef(ref, 3)}>{size}</div>
        </td>
        <td>
          <div ref={ref => backColumnRef && backColumnRef(ref, 4)}>{lastModified}</div>
        </td>
      </tr>
    )
  }
}

const dependencies = {controller: Controller}

export default inject(FileItem, dependencies)