import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';
import {setLayout} from './dataSlice'

const stateSlice = createSlice({
	name: 'quickPanel/state',
	initialState: false,
	reducers: {
		toggleQuickPanel: (state, action) => !state,
		openQuickPanel: (state, action) => true,
		closeQuickPanel: (state, action) => false,
	}
});

export const toggleQuickPanelLayout = createAsyncThunk('quickPanel/toggleLayout', async (layout, {dispatch}) =>{
	dispatch(toggleQuickPanel())
	dispatch(setLayout(layout))
})

export const {   toggleQuickPanel, openQuickPanel, closeQuickPanel } = stateSlice.actions;

export default stateSlice.reducer;
