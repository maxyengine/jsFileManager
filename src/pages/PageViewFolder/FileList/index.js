import React from 'react'
import FileItem from './FileItem'
import theme from './FileList.module.scss'
import ParentItem from './ParentItem'
import { connect } from 'react-redux'
import Spinner from './Spinner'
import { inject } from '@nrg/react-di'
import Controller from '../Controller'

class FileList extends React.Component {

  state = {
    resize: true,
    columns: [],
    tableHeight: null,
    bodyHeight: null
  }

  columns = []

  componentDidMount () {
    window.addEventListener('resize', this.onResize)
  }

  componentWillUnmount () {
    window.removeEventListener('resize', this.onResize)
  }

  componentDidUpdate (prevProps) {
    if (
      this.columns.length &&
      this.columns.some((column, index) => column !== this.state.columns[index])
    ) {
      const {appHeight, appWrapper} = this.props
      const bodyHeight = appHeight ?
        appHeight - this.body.offsetTop + appWrapper.offsetTop :
        document.documentElement.clientHeight - this.body.offsetTop
      const tableHeight = this.header.clientHeight + bodyHeight - 10

      this.setState({
        tableHeight,
        bodyHeight,
        columns: [...this.columns]
      })
    }

    if (this.activeItem && prevProps.activeIndex !== this.props.activeIndex) {
      const relativeTop = this.activeItem.getBoundingClientRect().top - this.body.getBoundingClientRect().top
      const screenHeight = this.body.offsetHeight - this.activeItem.offsetHeight

      //bottom position oriented scrolling
      if (relativeTop < 0 || relativeTop >= screenHeight) {
        this.body.scrollTop = this.activeItem.offsetTop - screenHeight + 10
      }

      //screen oriented scrolling
      // this.body.scrollTop = Math.floor((this.activeItem.offsetTop) / screenHeight) * screenHeight
    }
  }

  onResize = () => {
    this.setState({resize: !this.state.resize})
  }

  onBackColumnRef = (ref, index) => {
    if (ref) {
      this.columns[index] = ref.offsetWidth
    }
  }

  onBackRef = (ref) => {
    this.activeItem = ref
  }

  onActivateItem = (index) => {
    const {controller} = this.props

    controller.activateItem(index)
  }

  render () {
    const {directory, files, activeIndex} = this.props
    const {columns, bodyHeight, tableHeight} = this.state

    if (!directory) {
      return (
        <div className={theme.default} style={{height: tableHeight}}>
          <Spinner/>
        </div>
      )
    }

    const fileList = directory.parent ? [directory.parent, ...files] : files

    return (
      <div className={theme.default}>
        <div ref={ref => {this.header = ref}} style={{width: this.body && this.body.clientWidth}}
             className={theme.header}>
          <table>
            <tbody>
            <tr>
              <td>
                <div style={{width: columns[0]}}>&nbsp;</div>
              </td>
              <td>
                <div style={{width: columns[1]}}>Name</div>
              </td>
              <td>
                <div style={{width: columns[2]}}>Type</div>
              </td>
              <td>
                <div style={{width: columns[3]}}>Size</div>
              </td>
              <td>
                <div style={{width: columns[4]}}>Modified</div>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
        <div ref={ref => {this.body = ref}} style={{height: bodyHeight}} className={theme.body}>
          <table>
            <tbody>
            {fileList.map((file, index) => directory.parent === file ?
              <ParentItem
                key={'..'}
                file={file}
                isActive={activeIndex === index}
                backRef={activeIndex === index && this.onBackRef}
                onClick={() => this.onActivateItem(index)}
              /> :
              <FileItem
                key={file.path.value}
                file={file}
                isActive={activeIndex === index}
                backRef={activeIndex === index && this.onBackRef}
                backColumnRef={(index === files.length - 1) && this.onBackColumnRef}
                onClick={() => this.onActivateItem(index)}
              />
            )}
            </tbody>
          </table>
        </div>
      </div>
    )
  }
}

const mapStateToProps = ({directory, files, activeIndex}) => ({directory, files, activeIndex})
const dependencies = {
  appHeight: 'appHeight',
  appWrapper: 'appWrapper',
  controller: Controller
}

export default inject(connect(mapStateToProps)(FileList), dependencies)

