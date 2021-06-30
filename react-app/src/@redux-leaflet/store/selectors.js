import { createSelector } from '@reduxjs/toolkit'
import {defaults} from 'lodash-es'

const selectConfig = state => state.map.config
const selectOptions = state => state.map.options

export const selectMapOptions = createSelector(
	[selectConfig, selectOptions],
	(config, options) => {
		return defaults(config, options.map)
	}
)