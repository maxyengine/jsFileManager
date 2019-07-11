import { Client } from '@nrg/http'

export default class extends Client {

  static get services () {
    return {
      ...Client.services,
      fileFactory: 'fileFactory'
    }
  }

  get defaults () {
    return {
      ...super.defaults || {},
      storageId: 'uploads'
    }
  }

  async fetchLogin (data) {
    return await this.post('/login', data)
  }

  async fetchConfig () {
    return await this.post('/config')
  }

  async fetchDirectory (path) {
    return this.fileFactory.createDirectory(
      await this.post('/directory/read', {path: path || `${this.storageId}://`})
    )
  }

  async createDirectory (path) {
    return this.fileFactory.createDirectory(
      await this.post('/directory/create', {path: path})
    )
  }

  async deleteFile (path) {
    await this.post('/file/delete', {path})
  }

  openFile (path) {
    window.open(this.createUrl('/file/open', {Authorization: this.authorization(), path}), '_blank')
  }

  downloadFile (path) {
    window.open(this.createUrl('/file/download', {Authorization: this.authorization(), path}), '_blank')
  }

  createFileUploader (queryParams = {}, bodyParams = {}, request = {}) {
    return super.createFileUploader('/file/upload', queryParams, bodyParams, request)
  }
}