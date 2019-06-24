import React from 'react'
import { connect } from 'react-redux'
import StatusBar from '../StatusBar'
import FileList from '../FileList'
import NoMatchedFiles from '../NoMatchedFiles'

class ViewFolder extends React.Component {

  render () {
    const {directory, keywords, filteredFiles} = this.props

    const parent = directory.parent
    const files = '' === keywords ? directory.children : filteredFiles

    return (<>
      <StatusBar/>
      <FileList parent={parent} files={files}/>
    </>)
  }
}

const mapStateToProps = ({keywords, directory, filteredFiles}) => ({keywords, directory, filteredFiles})

export default connect(mapStateToProps)(ViewFolder)
