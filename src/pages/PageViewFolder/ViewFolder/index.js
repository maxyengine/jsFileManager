import React from 'react'
import { connect } from 'react-redux'
import FileList from '../FileList'
// import NoMatchedFiles from '../NoMatchedFiles'
import Toolbar from '../Toolbar'
import Breadcrumbs from '../Breadcrumbs'

class ViewFolder extends React.Component {

  render () {
    const {directory, keywords, filteredFiles} = this.props

    if (!directory) {
      return null
    }

    const parent = directory.parent
    const files = '' === keywords ? directory.children : filteredFiles

    return (<>
      <Toolbar/>
      <Breadcrumbs/>
      <FileList parent={parent} files={files}/>
    </>)
  }
}

const mapStateToProps = ({keywords, directory, filteredFiles}) => ({keywords, directory, filteredFiles})

export default connect(mapStateToProps)(ViewFolder)
