import React from 'react'
import { connect } from 'react-redux'
import theme from './Breadcrumbs.module.scss'
import { Link } from 'react-router-dom'
import { FaHome } from 'react-icons/fa'
import Spinner from './Spinner'

class Breadcrumbs extends React.Component {

  createCrumbs () {
    const {directory} = this.props
    const crumbs = []
    let path = directory.path

    while (path) {
      crumbs.unshift({
        fileName: path.fileName.value,
        pathname: '/',
        search: `?path=${encodeURIComponent(path)}`
      })

      path = path.parent
    }

    return crumbs
  }

  render () {
    const {directory} = this.props

    return (
      <div className={theme.default}>
        {directory ?
          this.createCrumbs().map(({fileName, pathname, search}, index) =>
            <Link key={search} to={{pathname, search}}>{0 === index ? <FaHome/> : fileName}</Link>
          ) :
          <Spinner/>}
      </div>
    )
  }
}

const mapStateToProps = ({directory}) => ({directory})

export default connect(mapStateToProps)(Breadcrumbs)