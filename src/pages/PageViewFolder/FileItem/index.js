import React from 'react'
import theme from './FileItem.module.scss'
import Controller from '../Controller'
import { inject } from '@nrg/react-di'
import { icon } from './helpers'
import Directory from '../../../entities/Directory'
import { Link } from 'react-router-dom'

const Component = class extends React.Component {

  constructor (props) {
    super(props)
    this.controller = this.props.controller
  }

  render () {
    const {file} = this.props
    const path = file.path.value
    const baseName = file.path.fileName.baseName
    const extension = file instanceof Directory ? 'dir' : file.path.fileName.extension
    const size = file.size && file.size.toHumanString()
    const lastModified = file.lastModified && file.lastModified.toLocaleString()

    return (
      <tr className={theme.default}>
        <td>
          <div className={theme.iconWrapper}>{icon(file)}</div>
        </td>
        <td>
          <div>
            <Link to={{pathname: '/', search: `?path=${path}`}}>{baseName}</Link>
          </div>
        </td>
        <td>
          <div>{extension}</div>
        </td>
        <td>
          <div>{size}</div>
        </td>
        <td>
          <div>{lastModified}</div>
        </td>
      </tr>
    )
  }
}

const dependencies = {controller: Controller}

export default inject(Component, dependencies)