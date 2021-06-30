var treeModel = require('tree-model');
var performantArrayToTree = require('performant-array-to-tree');
var flattree = require('flattree');
var nanoid = require('nanoid');
var lodashEs = require('lodash-es');

function _interopDefaultLegacy (e) { return e && typeof e === 'object' && 'default' in e ? e : { 'default': e }; }

var treeModel__default = /*#__PURE__*/_interopDefaultLegacy(treeModel);

function _extends() {
  _extends = Object.assign || function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];

      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }

    return target;
  };

  return _extends.apply(this, arguments);
}

var toTree = function toTree(items, config) {
  if (config === void 0) {
    config = {};
  }

  return performantArrayToTree.arrayToTree(items, _extends({
    parentId: 'parent_id',
    dataField: null
  }, config));
};

var toFlatTree = function toFlatTree(tree, options) {
  if (options === void 0) {
    options = {};
  }

  return flattree.flatten(tree, _extends({
    openAllNodes: true
  }, options));
};

function setIdsForTree(tree, _temp) {
  var _ref = _temp === void 0 ? {} : _temp,
      _ref$key = _ref.key,
      key = _ref$key === void 0 ? 'id' : _ref$key,
      _ref$id = _ref.id,
      id = _ref$id === void 0 ? nanoid.nanoid : _ref$id;

  if (lodashEs.isPlainObject(tree)) {
    tree[key] = lodashEs.isNil(tree[key]) ? id() : tree[key];
    if (tree.children) return _extends({}, tree, {
      children: tree.children.map(function (c) {
        return setIdsForTree(c);
      })
    });
  } else if (lodashEs.isArray(tree)) {
    return tree.map(function (i) {
      return setIdsForTree(i);
    });
  }

  return tree;
}

var generateIdsForTree = function generateIdsForTree(tree, options) {
  return setIdsForTree(lodashEs.cloneDeep(tree), options);
};

Object.defineProperty(exports, 'TreeModel', {
  enumerable: true,
  get: function () {
    return treeModel__default['default'];
  }
});
exports.generateIdsForTree = generateIdsForTree;
exports.toFlatTree = toFlatTree;
exports.toTree = toTree;
//# sourceMappingURL=index.js.map
