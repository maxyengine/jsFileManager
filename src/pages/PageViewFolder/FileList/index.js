import React from 'react'
import FileItem from '../FileItem'
import theme from './FileList.module.scss'

const Component = class extends React.Component {

  render () {
    const {files} = this.props

    return (
      <div className={theme.default}>
        <table>
          <tbody>
          {files.map(
            file => {
              return <FileItem key={file.path.value} file={file}/>
            }
          )}
          </tbody>
        </table>
      </div>
    )
  }
}

export default Component
