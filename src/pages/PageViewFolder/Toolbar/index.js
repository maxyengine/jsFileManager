import React from 'react'
import { connect } from 'react-redux'
import { inject } from '@nrg/react-di'
import Controller from '../Controller'
import theme from './Toolbar.module.scss'
import { FaPlus, FaUpload, FaRegCopy, FaFileArchive } from 'react-icons/fa'
import { MdClose } from 'react-icons/md'
import Dropdown from '../../../components/Dropdown'

class Toolbar extends React.Component {

  newFolder = () => {
    const {controller} = this.props
    controller.newFolderModal(true)
  }

  newFile = () => {
    console.log('newFile')
  }

  newHyperlink = () => {
    console.log('newHyperlink')
  }

  render () {
    /*const {keywords, directory, filteredFiles} = this.props
    const files = directory.isEmpty ? [] : directory.children
    const count = ('' === keywords ? files : filteredFiles).length*/

    return (
      <div className={theme.default}>
        <Dropdown
          icon={<FaPlus/>}
          items={[
            {label: 'Folder', onClick: this.newFolder},
            {label: 'File', onClick: this.newFile},
            {label: 'Hyperlink', onClick: this.newHyperlink}
          ]}
        />
        <button><FaUpload/></button>
        <button><FaRegCopy/></button>
        <button><MdClose/></button>
        <button><FaFileArchive/></button>
      </div>
    )
  }
}

const mapStateToProps = ({keywords, directory, filteredFiles}) => ({keywords, directory, filteredFiles})
const dependencies = {controller: Controller}

export default inject(connect(mapStateToProps)(Toolbar), dependencies)