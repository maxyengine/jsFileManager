import {
  ACTION_FETCH_DIRECTORY,
  ACTION_SEARCH_FILES,
  ACTION_NEW_FOLDER_MODAL, ORDER_DIRECTION_ASC
} from '../constants'
import TraitController from './TraitController'
import Directory from '../../../entities/Directory'

const isset = (value) => {
  return null !== value && undefined !== value
}

const isString = (value) => {
  return typeof value === 'string' || value instanceof String
}

const getFieldValue = (file, field) => {
  switch (field) {
    case 'name':
      return file.path.fileName.value
    case 'extension':
      return file.path.fileName.extension
    case 'size':
      return file.size && file.size.value
    case 'lastModified':
      return file.lastModified.getTime()
    default:
      return file[field]
  }
}

export default class DirectoryController extends TraitController {

  static get services () {
    return {
      client: 'client',
    }
  }

  get assignments () {
    return [
      'fetchDirectory',
      'createDirectory',
      'newFolderModal',
      'search'
    ]
  }

  async fetchDirectory (path) {
    this.action(ACTION_FETCH_DIRECTORY, {
      directory: null,
      files: [],
      keywords: '',
      activeIndex: 0
    })

    const directory = await this.client.fetchDirectory(path)
    const {orderBy} = this.state

    this.action(ACTION_FETCH_DIRECTORY, {
      directory,
      files: this.orderFiles(directory.children, orderBy)
    })
  }

  newFolderModal (isOpen) {
    this.action(ACTION_NEW_FOLDER_MODAL, {newFolderModal: isOpen})
  }

  async createDirectory ({path}) {
    const {directory} = this.state
    const newPath = directory.path.join(path, true)

    return await this.client.createDirectory(newPath.value)
  }

  search (keywords) {
    const {directory} = this.state
    const files = directory.isEmpty ? [] : directory.children
    keywords = keywords.toLocaleLowerCase()

    this.action(ACTION_SEARCH_FILES, {
      keywords,
      filteredFiles: '' === keywords ? files : this.filterFiles(files, keywords)
    })
  }

  filterFiles (files, keywords) {
    return '' === keywords ?
      files :
      files.filter(file => (file.path.fileName.value.toLocaleLowerCase().includes(keywords)))
  }

  orderFiles (files, orderBy) {
    let result = files

    orderBy.forEach(({field, direction}) => {
      const indexMap = new Map()
      result.forEach((data, index) => indexMap.set(data, index))

      result = result.sort((a, b) => {
        const indexOrder = indexMap.get(a) - indexMap.get(b)

        if (a instanceof Directory && !(b instanceof Directory)) {
          return -1
        } else if (b instanceof Directory && !(a instanceof Directory)) {
          return 1
        }

        const valueA = getFieldValue(a, field)
        const valueB = getFieldValue(b, field)

        if (isset(valueA) && !isset(valueB)) {
          return 1
        } else if (!isset(valueA) && isset(valueB)) {
          return -1
        } else if (!isset(valueA) && !isset(valueB)) {
          return indexOrder
        }

        const multiplier = ORDER_DIRECTION_ASC === direction ? 1 : -1

        if (isString(valueA)) {
          return multiplier * valueA.toLocaleLowerCase().localeCompare(valueB.toLocaleLowerCase()) || indexOrder
        } else {
          return multiplier * (valueA > valueB ? 1 : (valueB > valueA ? -1 : 0)) || indexOrder
        }
      })
    })

    return result
  }
}