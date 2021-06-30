import {flatten} from 'flattree'

const toFlatTree = (tree, options = {}) => flatten(tree, {
	openAllNodes: true,
	...options
})
export default toFlatTree
