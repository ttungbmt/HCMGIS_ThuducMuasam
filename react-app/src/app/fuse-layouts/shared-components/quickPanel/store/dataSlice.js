import {createAsyncThunk, createSelector, createSlice} from '@reduxjs/toolkit';
import {find} from 'lodash'
import axios from 'axios';

// export const getData = createAsyncThunk('quickPanel/data/getData', async () => {
// 	const response = await axios.get('/api/quick-panel/data');
// 	const data = await response.data;
//
// 	return data;
// });

export const layouts = [
    {
        name: 'basemap',
        title: 'Basemap',
        tooltip: 'Basemap',
        icon: 'fad fa-layer-group',
    },
    {
        name: 'legend',
        title: 'Basemap',
        tooltip: 'Legend',
        icon: 'fal fa-info-circle',
        width: 250,
    },
]

const dataSlice = createSlice({
    name: 'quickPanel/data',
    initialState: {
        layout: {
            name: 'basemap',
            title: 'Basemap',
        },
    },
    reducers: {
        setLayout(state, {payload: name}) {
            state.layout = find(layouts, {name}) ?? layouts[0]
        },
    },
    extraReducers: {
        // [getData.fulfilled]: (state, action) => action.payload
    }
});

export const {setLayout} = dataSlice.actions;

const selectSelf = (state) => state.quickPanel.data

export const selectLayout = createSelector(selectSelf, (state) => state.layout)

export default dataSlice.reducer;
