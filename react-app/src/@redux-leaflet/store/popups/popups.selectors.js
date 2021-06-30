import { createSelector } from '@reduxjs/toolkit';
import { findIndex, get } from 'lodash';
import layersAdapter from './popups.adapter';
import { layersSelectors } from '../index';
import { selectActiveLayers } from '../layers/layers.selectors';
import {find} from 'lodash-es'

export const selectModel = state => state.map.popups;

export const {
	selectById,
	selectIds,
	selectEntities,
	selectAll,
	selectTotal
} = layersAdapter.getSelectors(selectModel);

export const selectMaxRequest = createSelector([selectModel], (model) => model.maxRequest);

export const selectCurrentId = createSelector([selectModel], (model) => model.currentId);

export const selectCurrent = createSelector([selectCurrentId, selectEntities, selectActiveLayers], (currentId, entities, layers) => {
	let current = entities[currentId]
	if(!current) return null

	let template = get(find(layers, {id: current.layer_id}), 'popup')
	return {...current, template: current.template ? current.template : template}
});

export const selectPrevCurrent = createSelector([selectCurrentId, selectIds, selectEntities], (currentId, ids, entities) => {
	let index = findIndex(ids, i => i === currentId);
	if (index < 0) return null;
	return ids[index - 1] ? entities[ids[index - 1]] : null;
});

export const selectNextCurrent = createSelector([selectCurrentId, selectIds, selectEntities], (currentId, ids, entities) => {
	let index = findIndex(ids, i => i === currentId);
	if (index < 0) return null;
	return ids[index + 1] ? entities[ids[index + 1]] : null;
});


export const selectLayerPopup = createSelector([
	layersSelectors.selectEntities,
	selectCurrent
], (layers, current) => {
	if (!current) return null;
	let layerPopup = get(layers, `${current.layer_id}.popup`);
	return Object.assign({}, layerPopup, current);
});
