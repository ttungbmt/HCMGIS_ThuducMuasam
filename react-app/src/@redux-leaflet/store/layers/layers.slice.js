import { createSlice } from '@reduxjs/toolkit'
import layersAdapter from './layers.adapter'
import * as actions from './layers.actions'
import {filter, includes, update as updateIn, set as setIn, unset as unsetIn, mapKeys, get, map, isEmpty} from 'lodash'
import {original, produce} from "immer"
// layersAdapter.updateMany(state, payload.ids.map(id => ({ id, changes: { active: payload.selected } })))

const layersSlice = createSlice({
	name: 'map/layers',
	initialState: layersAdapter.getInitialState({
		loading: false,
		defaults: {}
	}),
	reducers: {
		activeOne: (state, { payload: { ids, active, siblingIds = []} }) => {
			if(isEmpty(siblingIds)){
				map(filter(state.entities, e => e.control === 'basemap'), 'id').map(id => state.entities[id].active = !active)
				// filter()state.ids.map(id => state[])
			} else siblingIds.map(id => state.entities[id].active = !active)

			ids.map(id => state.entities[id].active = active)
		},
		active: (state, { payload }) => {
			payload.ids.map(id => {
				if(state.entities[id]) state.entities[id].active = payload.active
			})
		},
		removeLayer: layersAdapter.removeOne,
		setCqlFilter: (state, {payload}) => {
			let entity = state.entities[payload.id]
			setIn(entity, 'source.params.cql_filter', payload.data)
		},
		clearCqlFilter: (state, {payload}) => {
			let defaultEntity = state.defaults[payload],
				entity = state.entities[payload]

			setIn(entity, 'source.params.cql_filter', get(defaultEntity, 'source.params.cql_filter', '1=1'))
		},
		setLayers: (state, {payload}) => {
			layersAdapter.upsertMany(state, payload)
			state.defaults = mapKeys(payload, 'id')
		}
	},
	extraReducers: builder => {
		// builder.addCase(actions.fetchLayers.pending, (state, action) => {
		// 	state.loading = true
		// })
		// builder.addCase(actions.fetchLayers.fulfilled, (state, { payload }) => {
		// 	layersAdapter.upsertMany(state, payload.flatLayers)
		// 	state.defaults = mapKeys(payload.flatLayers, 'id')
		// 	state.loading = false
		// })
	}
})

export const layersActions = {
	...layersSlice.actions,
	...actions
}

export default layersSlice.reducer
