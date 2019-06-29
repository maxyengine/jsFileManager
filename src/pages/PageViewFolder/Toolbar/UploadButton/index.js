import React from 'react'
import theme from './UploadButton.module.scss'
import { FaUpload } from 'react-icons/fa'

class UploadButton extends React.Component {

  onBrowseFiles = () => {
    this.fileInput.click()
  }

  render () {
    const {onSelectFiles} = this.props

    return (
      <button className={theme.default} onClick={this.onBrowseFiles}>
        <FaUpload/>
        <input
          type="file"
          multiple={true}
          ref={ref => this.fileInput = ref}
          onChange={(e) => {
            onSelectFiles && onSelectFiles(e)
            this.fileInput.value = null
          }}
        />
      </button>
    )
  }
}

export default UploadButton