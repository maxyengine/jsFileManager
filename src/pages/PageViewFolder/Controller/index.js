import UploadController from './UploadController'
import DirectoryController from './DirectoryController'
import ConfigController from './ConfigController'
import FileController from './FileController'
import StoreController from './StoreController'
import { ORDER_BY_DEFAULT, ORDER_DIRECTION_ASC } from '../constants'
import KeyNavigationController from './KeyNavigationController'

export default class Controller extends StoreController {

  get initState () {
    return {
      config: {},
      directory: null,
      files: [],
      keywords: '',
      orderBy: [
        {
          field: ORDER_BY_DEFAULT,
          direction: ORDER_DIRECTION_ASC
        }
      ],
      uploadList: [],
      activeIndex: 0,
      focusedComponent: null,
      newFolderModal: false,
      uploadFilesModal: false
    }
  }

  get traits () {
    return [
      ConfigController,
      FileController,
      DirectoryController,
      UploadController,
      KeyNavigationController
    ]
  }

  setFocusedComponent (focusedComponent) {
    this.action('SET_FOCUSED_COMPONENT', {focusedComponent})
  }

  getFocusedComponent () {
    return this.state.focusedComponent
  }
}