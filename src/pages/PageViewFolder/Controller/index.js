import UploadController from './UploadController'
import DirectoryController from './DirectoryController'
import ConfigController from './ConfigController'
import FileController from './FileController'
import StoreController from './StoreController'

export default class Controller extends StoreController {

  get initState () {
    return {
      config: {},
      directory: null,
      keywords: '',
      filteredFiles: [],
      uploadList: [],
      newFolderModal: false,
      uploadFilesModal: false
    }
  }

  get traits () {
    return [
      ConfigController,
      FileController,
      DirectoryController,
      UploadController
    ]
  }
}