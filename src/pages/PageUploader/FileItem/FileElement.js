import { Element } from '@nrg/form'
import FileExtensionValidator from './FileExtensionValidator'
import FileSizeValidator from './FileSizeValidator'

export default class extends Element {

  initialize () {
    this
      .addValidator(new FileExtensionValidator(this.config))
      .addValidator(new FileSizeValidator(this.config))
  }
}
