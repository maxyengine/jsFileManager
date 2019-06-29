import React from 'react'
import FileItem from '../FileItem'
import theme from './FileList.module.scss'
import ParentItem from '../ParentItem'
import { connect } from 'react-redux'
import Spinner from './Spinner'

class FileList extends React.Component {

  render () {
    const {directory, keywords, filteredFiles} = this.props

    if (!directory) {
      return (
        <div className={theme.default}>
          <Spinner/>
        </div>
      )
    }

    const parent = directory.parent
    const files = '' === keywords ? directory.children : filteredFiles

    return (
      <div className={theme.default}>
        <table>
          <tbody>
          {parent && <ParentItem parent={parent}/>}
          {files.map(file => <FileItem key={file.path.value} file={file}/>)}
          </tbody>
        </table>
      </div>
    )
  }
}

const mapStateToProps = ({keywords, directory, filteredFiles}) => ({keywords, directory, filteredFiles})

export default connect(mapStateToProps)(FileList)

