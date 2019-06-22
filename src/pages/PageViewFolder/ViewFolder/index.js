import React from 'react'
import { connect } from 'react-redux'
import EmptyFolder from '../EmptyFolder'
import StatusBar from '../StatusBar'
import FileList from '../FileList'
import NoMatchedFiles from '../NoMatchedFiles'

const Component = class extends React.Component {

  render () {
    const {keywords, files, filteredFiles} = this.props

    if (!files.length) {
      return (
        <EmptyFolder/>
      )
    }

    return (<>
      <StatusBar/>
      {
        files.length ?
          <FileList files={'' === keywords ? files : filteredFiles}/> :
          <NoMatchedFiles/>
      }
    </>)
  }
}

const mapStateToProps = ({keywords, files, filteredFiles}) => ({keywords, files, filteredFiles})

export default connect(mapStateToProps)(Component)
