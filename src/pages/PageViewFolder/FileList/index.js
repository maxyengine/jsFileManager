import React from 'react'
import FileItem from '../FileItem'
import theme from './FileList.module.scss'

const Component = class extends React.Component {

  render () {
    const {files} = this.props

    return (
      <ul className={theme.default}>
        {files.map(
          file => <FileItem key={file.path.value} file={file}/>
        )}
      </ul>
    )
  }
}

export default Component
