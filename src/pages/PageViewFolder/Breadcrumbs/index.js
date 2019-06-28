import React from 'react'
import { connect } from 'react-redux'
import theme from './Breadcrumbs.module.scss'
import { Link } from 'react-router-dom'
import {FaHome} from 'react-icons/fa'

class Breadcrumbs extends React.Component {

  createCrumbs () {
    const {directory} = this.props
    const crumbs = []
    let path = directory.path

    while (path) {
      crumbs.unshift({
        fileName: path.fileName.value,
        pathname: '/',
        search: `?path=${path}`
      })

      path = path.parent
    }

    return crumbs
  }

  render () {
    const crumbs = this.createCrumbs()

    return (
      <div className={theme.default}>
        {crumbs.map(({fileName, pathname, search}, index) => {
          return 0 === index ?
            <Link key={search} to={{pathname, search}}><FaHome/></Link> :
            <Link key={search} to={{pathname, search}}>{fileName}</Link>
        })}
      </div>
    )
  }
}

const mapStateToProps = ({directory}) => ({directory})

export default connect(mapStateToProps)(Breadcrumbs)