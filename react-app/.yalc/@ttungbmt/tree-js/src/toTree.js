import {arrayToTree} from 'performant-array-to-tree'

const toTree = (items, config = {}) => arrayToTree(items, {
	parentId: 'parent_id',
	dataField: null,
	...config
})

export default toTree