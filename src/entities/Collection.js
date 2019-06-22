const data = Symbol()
const total = Symbol()
const limit = Symbol()
const offset = Symbol()

export default class {

  constructor (raw) {
    this[data] = raw.data || []
    this[total] = raw.total || this[data].length
    this[limit] = raw.limit
    this[offset] = raw.offset
  }

  get data () {
    return this[data]
  }

  get total () {
    return this[total]
  }

  get limit () {
    return this[limit]
  }

  get offset () {
    return this[offset]
  }

  [Symbol.iterator] () {
    return this[data].entries()
  }
}
