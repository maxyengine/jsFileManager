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

  async fetchUploadsFolder (path) {
    const raw = await this.post('/list', {path: path || `${this.storageId}://`})

    return this.fileFactory.createDirectory(raw)
  }

  createFileUploader () {
    return super.createFileUploader('/upload', {}, {
      path: `${this.storageId}://`,
    })
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
}