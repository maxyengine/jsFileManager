import React from 'react'
import { connect } from 'react-redux'
import { inject } from '@nrg/react-di'
import Controller from '../Controller'
import theme from './StatusBar.module.scss'

const Component = class extends React.Component {

  constructor (props) {
    super(props)
    this.controller = this.props.controller
  }

  onSearch = (event) => {
    this.controller.search(event.target.value)
  }

  onShowAll = () => {
    this.controller.search('')
  }

  render () {
    const {keywords, files, filteredFiles} = this.props
    const count = ('' === keywords ? files : filteredFiles).length

    return (
      <div className={theme.default}>
        <span>Files: {count}</span>
        <div>
          <input
            type={'text'}
            name={'keywords'}
            value={keywords}
            placeholder={'Search'}
            autoComplete={null}
            onChange={this.onSearch}
          />
          <button title="Show All" onClick={this.onShowAll}>
            Show All
          </button>
        </div>
      </div>
    )
  }
}

const mapStateToProps = ({keywords, files, filteredFiles}) => ({keywords, files, filteredFiles})
const dependencies = {controller: Controller}

export default inject(connect(mapStateToProps)(Component), dependencies)