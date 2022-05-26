const template = require('babel-template');
const babelTransform = require('babel-plugin-transform-strict-mode');

const wrapper = template(`document.addEventListener('DOMContentLoaded', () => {{BODY}});`);
module.exports = function (babel) {
  const t = babel.types;

  return {
    inherits: babelTransform,
    visitor: {
      Program: {
        exit(path) {
          if (!this.isWrapped) {
            this.isWrapped = true;

            path.replaceWith(
              t.program([wrapper({
                BODY: path.node.body,
              })]),
            );
          }
          path.node.directives = [];
        },
      },
    },
  };
};
