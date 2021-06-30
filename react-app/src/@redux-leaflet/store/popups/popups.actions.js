import { createAsyncThunk } from '@reduxjs/toolkit'
import { nanoid } from 'nanoid'
import { isEmpty, get, toInteger, findIndex, merge } from 'lodash'
import { popupsActions } from './popups.slice'
import { layersSelectors, popupsSelectors } from '../index'
// import { formSelectors } from 'app/store/form'
import { toCenter } from '../../utils/geom'

export const getFeatureWMS = createAsyncThunk('map/popups/getFeatureWMS', async (payload, { dispatch, getState, rejectWithValue }) => {
	let {maxRequest} = popupsSelectors.selectModel(getState())
	let	items = payload.map(p => ({ id: nanoid(), ...p })),
		indexSuccess = 0

	dispatch(popupsActions.init(items))

	for (let i in items) {
		try {
			let index = toInteger(i),
				pop = items[index]

			if (index + 1 > maxRequest) break

			const response = await fetch(pop.url)
			const data = await response.json()
			let feature = get(data, 'features.0', {})

			if (!isEmpty(feature)) {
				indexSuccess++

				dispatch(popupsActions.setInfo({
					id: pop.id,
					changes: {
						info: {
							...feature.properties,
							geometry: feature.geometries
						}
					}
				}))

				if (indexSuccess === 1) dispatch(popupsActions.setCurrentId(pop.id))
				else if (indexSuccess === 2) break
			}
		} catch (err) {
			console.error(err)
		}
	}
})

export const getFeatureWFS = createAsyncThunk('map/popups/getFeatureWFS', async (payload) => {
	console.log(payload)
})

// export const getFeatureForm = createAsyncThunk('map/popups/getFeatureForm', async (payload, {dispatch, getState}) => {
// 	if(!payload.geometry) return null
//
// 	let state = getState(),
// 		layerKey = get(formSelectors.selectCurrent(state), 'popup.linkLayer'),
// 		layer = layersSelectors.selectBy(state, {key: layerKey}),
// 		id = nanoid()
//
// 	if(layer && layer.popup) {
// 		dispatch(popupsActions.init([{
// 			id,
// 			layer_id: layer.id,
// 			latlng: toCenter(payload.geometry),
// 			info: payload
// 		}]))
// 		dispatch(popupsActions.setCurrentId(id))
// 	}
//
// })

export const nextPopup = createAsyncThunk('map/popups/fetchNext', async (nextId, { dispatch, getState }) => {
	let {maxRequest} = popupsSelectors.selectModel(getState())
	let items = popupsSelectors.selectAll(getState()),
		nextIdx = findIndex(items, { id: nextId })

	if (nextIdx < 0) return

	let nextItems = items.slice(nextIdx+1)

	dispatch(popupsActions.setCurrentId(nextId))

	for (let index in nextItems) {
		let idx = toInteger(index),
			value = nextItems[index]

		if (index + 1 > maxRequest || !isEmpty(value.info)) break
		try {
			const response = await fetch(value.url)
			const data = await response.json()
			let feature = get(data, 'features.0', {})

			if (!isEmpty(feature)) {
				dispatch(popupsActions.setInfo({ id: value.id, changes: { info: merge(feature.properties, {geometry: feature.geometries})}}))
				break
			}
		} catch (err) {
			console.error(err)
		}
	}
})

