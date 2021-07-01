import { createSlice } from '@reduxjs/toolkit';
import { map } from 'lodash-es';

import * as actions from './config.actions';

const configSlice = createSlice({
	name: 'map/config',
	initialState: {
		loading: true,
		style: { width: '100%', height: '100%', backgroundColor: 'white' },
		center: [10.804476, 106.639384],
		zoom: 10,
	},
	reducers: {
		setCenter: (state, { payload }) => {
			state.center = payload;
		},
		setZoom: (state, { payload }) => {
			state.zoom = payload;
		},
		setBounds: (state, { payload }) => {
			state.bounds = payload;
		},
		setConfig: (state, { payload }) => {
			map(payload, (value, name) => state[name] = value);
		},
		setLoading: (state, { payload }) => {
			state.loading = payload
		},
	}
});

export const configActions = {
	...configSlice.actions,
	...actions
};

export default configSlice.reducer;
