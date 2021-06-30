import { createAsyncThunk } from '@reduxjs/toolkit'
import layersAPI from './layers.api'

export const fetchLayers = createAsyncThunk('map/layers/fetchAll', async (url) => {
	const response = await layersAPI.fetchAll(url)
	return response.data
})

