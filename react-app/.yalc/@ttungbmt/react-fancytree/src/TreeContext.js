import { useState, useCallback, useEffect } from 'react';
import constate from 'constate';
import { useImmerReducer } from 'use-immer';
import { filter, includes, keys, map, mapKeys, mapValues, get } from 'lodash';
import {original} from "immer"
import { toTree } from '@ttungbmt/tree-js';

const initialState = {
	loading: false,
	ids: [],
	entities: {}
};

const updateIds = state => void (state.ids = keys(state.entities))

const caseReducers = {
	setLoading: (state, { payload }) => state.loading = payload,
	setAll: (state, { payload }) => {
		state.entities = mapKeys(payload, 'id')
		updateIds(state)
	},
	select: (state, {payload}) => {
		payload.ids.map(id => state.entities[id].selected = payload.selected)
	},
	selectOne: (state, { payload: {ids, selected, siblingIds} }) => {
		siblingIds.map(id => state.entities[id].selected = !selected)
		ids.map(id => state.entities[id].selected = selected)
	},
	setExpand: (state, {payload: id}) => {
		if(state.entities[id]) state.entities[id].expanded = true
	},
	setCollapse: (state, {payload: id}) => {
		if(state.entities[id]) state.entities[id].expanded = false
	}
};

const reducer = (draft, action) => void caseReducers[action.type](draft, action);

function useTree() {
	const [state, dispatch] = useImmerReducer(reducer, initialState);
	const [tree, setTree] = useState(undefined);

	const actions = useCallback(() => ({
		...mapValues(caseReducers, (action, type) => (payload) => dispatch({ type, payload })),
		expandAll: () => tree.expandAll(),
		collapseAll: () => tree.expandAll(false),
		toggleExpandAll: () => tree.visit((node) => node.toggleExpanded()),
	}), [tree]);

	return {
		...actions(),
		tree,
		setTree,
		dispatch,
		source: toTree(state.ids.map(id => state.entities[id])),
	};
}

const [TreeProvider, useTreeContext] = constate(useTree);

export {
	TreeProvider,
	useTreeContext
};