import { Element } from '@nrg/form'
import FileExtensionValidator from '../validators/FileExtensionValidator'
import FileSizeValidator from '../validators/FileSizeValidator'

export default class extends Element {

  initialize () {
    this
      .addValidator(new FileExtensionValidator(this.config))
      .addValidator(new FileSizeValidator(this.config))
  }
}
