import React from 'react'
import { connect } from 'react-redux'
import EmptyFolder from '../EmptyFolder'
import StatusBar from '../StatusBar'
import FileList from '../FileList'
import NoMatchedFiles from '../NoMatchedFiles'

const Component = class extends React.Component {

  render () {
    const {keywords, directory, filteredFiles} = this.props

    if (directory.isEmpty) {
      return (
        <EmptyFolder/>
      )
    }

    return (<>
      <StatusBar/>
      <FileList files={'' === keywords ? directory.children : filteredFiles}/>
    </>)
  }
}

const mapStateToProps = ({keywords, directory, filteredFiles}) => ({keywords, directory, filteredFiles})

export default connect(mapStateToProps)(Component)
