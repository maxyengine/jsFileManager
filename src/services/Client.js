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
      storageId: 'uploads',
      directFileAccess: false,
      directFileUrl: '/uploads'
    }
  }

  async fetchLogin (data) {
    return await this.post('/login', data)
  }

  async fetchConfig () {
    return await this.post('/config')
  }

  async fetchDirectory (path) {
    const raw = await this.post('/directory/read', {path: path || `${this.storageId}://`})

    return this.fileFactory.createDirectory(raw)
  }

  async createDirectory (path) {
    const raw = await this.post('/directory/create', {path: path})

    return this.fileFactory.createDirectory(raw)
  }

  async deleteFile (path) {
    await this.post('/delete', {path})
  }

  openFile (fileName) {
    if (this.directFileAccess) {
      window.open(
        this.createPrettyUrl(`${this.directFileUrl}/${fileName}`),
        '_blank'
      )
    } else {
      window.open(
        this.createUrl('/open', {
          path: `${this.storageId}://${fileName}`,
          Authorization: this.authorization()
        }),
        '_blank'
      )
    }
  }

  downloadFile (fileName) {
    window.open(
      this.createUrl('/download', {
        path: `${this.storageId}://${fileName}`,
        Authorization: this.authorization()
      }),
      '_blank'
    )
  }

  createFileUploader (queryParams = {}, bodyParams = {}, request = {}) {
    return super.createFileUploader('/file/upload', queryParams, bodyParams, request)
  }
}