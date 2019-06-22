import { Form, Element, TrimFilter, RequiredValidator, EmailValidator } from '@nrg/form'

export default class extends Form {

  initialize () {
    this
      .addElement(
        new Element({name: 'email'})
          .addFilter(new TrimFilter())
          .addValidator(new RequiredValidator())
          .addValidator(new EmailValidator())
      )
      .addElement(
        new Element({name: 'password'})
          .addFilter(new TrimFilter())
          .addValidator(new RequiredValidator())
      )
  }
}
