const path = require('path');

module.exports = {
  browser: true,
  verbose: true,

  // Configures the test
  setupTestFrameworkScriptFile: path.resolve(__dirname + "/assets/src/testSetup.js"),
  snapshotSerializers: ["enzyme-to-json/serializer"]
};
