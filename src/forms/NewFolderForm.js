import { Form, Element, TrimFilter, RequiredValidator } from '@nrg/form'

class NewFolderForm extends Form {

  initialize () {
    this
      .addElement(
        new Element({name: 'path'})
          .addFilter(new TrimFilter())
          .addValidator(new RequiredValidator())
      )
  }
}

export default NewFolderForm