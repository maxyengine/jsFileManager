module.exports = function override (config, env) {

  config.optimization.runtimeChunk = false
  config.optimization.splitChunks = {
    cacheGroups: {
      default: false
    }
  }

  return config
}