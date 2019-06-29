import React from 'react'
import { FaFolder, FaFileImage, FaFile, FaFilePdf } from 'react-icons/fa'
import Directory from '../../../../entities/Directory'

//https://react-icons.netlify.com/#/icons/md

export const icon = (file) => {
  if (file instanceof Directory) {
    return <FaFolder/>
  }

  switch (file.path.fileName.extension.toLocaleLowerCase()) {
    case 'png':
      return <FaFileImage/>
    case 'pdf':
      return <FaFilePdf/>
    default:
      return <FaFile/>
  }
}