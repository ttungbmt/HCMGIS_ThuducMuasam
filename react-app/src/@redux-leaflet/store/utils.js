import { nanoid } from 'nanoid';
import { transform, isString, get, isEmpty, map, includes, set, merge, isPlainObject, isArray, cloneDeep, omit, pick, defaults } from 'lodash';
import { toFlatTree } from '@ttungbmt/tree-js';
import basemaps from '../fixtures/basemaps';

const parseQuery = (queryString) => {
	let query = {};
	let pairs = (queryString[0] === '?' ? queryString.substr(1) : queryString).split('&');
	for (let i = 0; i < pairs.length; i++) {
		let pair = pairs[i].split('=');
		query[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
	}
	return query;
};

const getBasemap = (name) => {
	let key = 'none', params = {};
	if (isString(name)) {
		let arr = name.split(':');
		key = arr[0];
		params = (arr[1] && isString(arr[1])) ? parseQuery(arr[1]) : {};
	} else if (isArray(name)) {
		key = name[0];
		params = name[1];
	}

	if (params.active) params.active = JSON.parse(params.active);

	return merge(get(basemaps, key, {}), params);
};

const parseLayerItem = (item) => {
	return isPlainObject(item) ? Object.assign({}, item) : getBasemap(item);
};

export const transformLayers = (raw) => {
	return transform(raw, function(result, n) {
		let layer = parseLayerItem(n),
			obj = defaults({
				...layer.tree,
				...pick(layer, ['title', 'children']),
				data: omit(layer, ['children', 'tree'])
			}, {
				id: nanoid(),
			})

		if(layer.active) obj.selected = layer.active

		if (!isEmpty(obj.children)) {
			obj.folder = true;
			obj.children = transformLayers(obj.children);
		}


		result.push(obj);
	}, []);
};

export const transformFlatTree = (tree) => transform(toFlatTree(cloneDeep(tree)), (result, node) => {
	let obj = {};
	map(node, (v, k) => {
		if (k === 'parent') set(obj, 'parent_id', v.id);
		if (!includes(['state', 'parent', 'children'], k)) set(obj, k, v);
	});
	result.push(obj);
}, []);