import { nanoid } from 'nanoid'
import {isPlainObject, isNil, isArray, cloneDeep} from 'lodash-es'

function setIdsForTree(tree, {key = 'id', id = nanoid} = {}) {
	if(isPlainObject(tree)){
		tree[key] = isNil(tree[key]) ? id() : tree[key]

		if(tree.children) return {
			...tree,
			children: tree.children.map(c => setIdsForTree(c))
		}
	} else if(isArray(tree)) {
		return tree.map(i => setIdsForTree(i))
	}
	return tree
}

const generateIdsForTree = (tree, options) => setIdsForTree(cloneDeep(tree), options)

export default generateIdsForTree