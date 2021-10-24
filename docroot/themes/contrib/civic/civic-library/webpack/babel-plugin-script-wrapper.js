const template = require('babel-template');

const wrapper = template(`document.addEventListener('DOMContentLoaded', () => {{BODY}});`);
module.exports = function (babel) {
  const t = babel.types;

  return {
    inherits: require("babel-plugin-transform-strict-mode"),
    visitor: {
      Program: {
        exit(path) {
          if (!this.isWrapped) {
            this.isWrapped = true;

            path.replaceWith(
              t.program([wrapper({
                BODY: path.node.body
              })])
            );
          }
          path.node.directives = [];
        }
      }
    }
  };
};
