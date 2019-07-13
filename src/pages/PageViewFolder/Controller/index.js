import UploadController from './UploadController'
import DirectoryController from './DirectoryController'
import ConfigController from './ConfigController'
import FileController from './FileController'
import StoreController from './StoreController'
import { ORDER_BY_DEFAULT, ORDER_DIRECTION_ASC} from '../constants'

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