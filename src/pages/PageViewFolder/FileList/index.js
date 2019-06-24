import React from 'react'
import FileItem from '../FileItem'
import theme from './FileList.module.scss'
import ParentItem from '../ParentItem'

class FileList extends React.Component {

  render () {
    const {parent, files} = this.props

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

export default FileList
