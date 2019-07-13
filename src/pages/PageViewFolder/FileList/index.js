import React from 'react'
import FileItem from './FileItem'
import theme from './FileList.module.scss'
import ParentItem from './ParentItem'
import { connect } from 'react-redux'
import Spinner from './Spinner'
import { inject } from '@nrg/react-di'

class FileList extends React.Component {

  state = {
    resize: true,
    columns: [],
    bodyHeight: '100%'
  }

  columns = []

  componentDidMount () {
    window.addEventListener('resize', this.onResize)
  }

  componentWillUnmount () {
    window.removeEventListener('resize', this.onResize)
  }

  componentDidUpdate () {
    this.alignColumns()
  }

  onResize = () => {
    this.setState({resize: !this.state.resize})
  }

  alignColumns () {
    if (
      this.columns.length &&
      this.columns.some((column, index) => column !== this.state.columns[index])
    ) {
      const {appHeight, appWrapper} = this.props

      const bodyHeight = appHeight ?
        appHeight - this.body.offsetTop + appWrapper.offsetTop :
        document.documentElement.clientHeight - this.body.offsetTop

      this.setState({
        bodyHeight,
        columns: [...this.columns]
      })
    }
  }

  onBackRef = (ref, index) => {
    if (ref) {
      this.columns[index] = ref.offsetWidth
    }
  }

  alignHeight () {
    let
      appWrapper = document.body === this.layouts.wrapper.element ? document.documentElement : this.layouts.wrapper.element,
      wrapper = appWrapper

    if (this.owner.wrapper && this.owner.wrapper.element !== appWrapper) {
      wrapper = this.owner.wrapper.element
      const wrapperHeight = appWrapper.clientHeight - wrapper.offsetTop
      wrapper.style.height = wrapperHeight + 'px'
    }

    const
      element = this.owner.element,
      height = wrapper.clientHeight - element.offsetTop

    element.style.height = height + 'px'
  }

  render () {
    const {directory, files} = this.props
    const {columns, bodyHeight} = this.state

    if (!directory) {
      return (
        <div className={theme.default}>
          <Spinner/>
        </div>
      )
    }

    const parent = directory.parent

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
            {parent && <ParentItem parent={parent}/>}
            {files.map((file, index) => index === files.length - 1 ?
              <FileItem key={file.path.value} file={file} backRef={this.onBackRef}/> :
              <FileItem key={file.path.value} file={file}/>
            )}
            </tbody>
          </table>
        </div>
      </div>
    )
  }
}

const mapStateToProps = ({directory, files}) => ({directory, files})
const dependencies = {appHeight: 'appHeight', appWrapper: 'appWrapper'}

export default inject(connect(mapStateToProps)(FileList), dependencies)

