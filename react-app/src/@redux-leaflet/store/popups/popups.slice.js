import { createSlice } from '@reduxjs/toolkit'
import {nanoid} from 'nanoid'
import popupsAdapter from './popups.adapter'
import * as actions from './popups.actions'

const popupsSlice = createSlice({
	name: 'map/popups',
	initialState: popupsAdapter.getInitialState({
		currentId: null,
		loading: false,
		maxRequest: 2
	}),
	reducers: {
		setAll: (state, {payload}) => {
			popupsAdapter.setAll(state, payload.map(p => ({id: nanoid(), ...p})))
		},
		setCurrentId: (state, {payload}) => {
			state.currentId = payload
		},
		setInfo: (state, {payload}) => {
			popupsAdapter.updateOne(state, payload)
		},
		remove: (state, {payload: id}) => {
			popupsAdapter.removeOne(state, id)
		},
		removeAll: (state, payload) => {
			popupsAdapter.removeAll(state)
		},
		setLoading: (state, {payload}) =>  {
			state.loading = payload

			if(payload) document.querySelector('.leaflet-container').style.cursor = 'progress'
			else document.querySelector('.leaflet-container').style.cursor = 'inherit'
		}
	},
	extraReducers: builder => {
		builder.addCase(actions.getFeatureWMS.pending, (state, action) => {
			state.loading = true
		})

		builder.addCase(actions.getFeatureWMS.fulfilled, (state, { payload }) => {
			state.loading = false
		})

		builder.addCase(actions.getFeatureWMS.rejected, (state, action) => {
			state.loading = false
		})

		// builder.addCase(actions.getFeatureForm.pending, (state, action) => {
		// 	state.loading = true
		// })

		// builder.addCase(actions.getFeatureForm.fulfilled, (state, { payload }) => {
		// 	state.loading = false
		// })
	}
});

export const popupsActions = {
	...popupsSlice.actions,
	...actions
}

export default popupsSlice.reducer;
