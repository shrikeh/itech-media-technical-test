'use strict';

module.exports = {
  "env": {
    "development": {
      "plugins": ["transform-es2015-modules-commonjs"]
    },
    "test": {
      "plugins": ["transform-es2015-modules-commonjs"]
    }
  },
  presets: [
    [
      '@babel/preset-env',
      {
        targets: {
          node: 'current',
        },
      },
    ],
    '@babel/preset-react',
    '@babel/preset-typescript'
  ]
};
