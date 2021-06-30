import { createSelector } from '@reduxjs/toolkit'
import layersAdapter from './layers.adapter'
import { toTree } from '@ttungbmt/tree-js'
import {find, isPlainObject, pick} from 'lodash'

const selectGlobal = state => state.map.layers

export const {
	selectById,
	selectIds,
	selectEntities,
	selectAll,
	selectTotal,
} = layersAdapter.getSelectors(selectGlobal)


export const selectTree = createSelector([selectAll], (layers) => toTree(layers.map(({tree = {}, ...layer}) => {
	let item = {
		...tree,
		...pick(layer, ['id', 'title',  'parent_id']),
		data: layer
	}
	if(layer.active) item.selected = layer.active
	return item
})))

export const selectBasemaps = createSelector([selectAll], (layers) => layers.filter(l => (l.type && l.control === 'basemap')))

export const selectOverlays = createSelector([selectAll], (layers) => layers.filter(l => (l.type && l.control !== 'basemap')))

export const selectPopupOverlays = createSelector([selectOverlays], (layers) => layers.filter(l => l.popup))

export const selectLoading = createSelector([selectGlobal], (global) => global.loading)

export const selectActiveLayers = createSelector([selectAll], (layers) => layers.filter(l => (l.type && (l.selected || l.active))))

export const selectBy = createSelector([selectAll, (_, by) => by ], (layers, by) => {
	if(isPlainObject(by)) return find(layers, by)
})

export const selectByKey = createSelector([selectAll, (state, key) => key], (layers, key) => find(layers, {key}))