'use strict';
const moduleNameModule = require('jest-module-name-mapper');
const { resolve } = require('path');
const { defaults } = require('jest-config');
const jestTestPath = resolve(__dirname, 'tests/jest');

module.exports = {
  verbose: true,
  bail: true,
  moduleFileExtensions: [...defaults.moduleFileExtensions, 'ts', 'tsx'],
  moduleNameMapper: moduleNameModule('tsconfig.json'),
  rootDir: __dirname,
  roots: [
    'public/src',
    'tests/jest/src'
  ],
  testMatch: [
    "**/*.test.[jt]s?(x)"
  ],
  transform: {
    "^.+\\.tsx?$": "ts-jest"
  },
  setupFiles: [
    resolve(jestTestPath, 'bootstrap.ts')
  ],
  setupFilesAfterEnv: [
    "@testing-library/jest-dom/extend-expect"
  ],
  "automock": false
};
