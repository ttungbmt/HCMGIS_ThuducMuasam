import { createSlice } from '@reduxjs/toolkit';

const optionsSlice = createSlice({
	name: 'maps/options',
	initialState: {
		map: {
			zoomControl: false
		}
	},
	reducers: {}
});

export const {} = optionsSlice.actions;

export default optionsSlice.reducer;
