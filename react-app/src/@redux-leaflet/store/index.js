import { combineReducers } from '@reduxjs/toolkit'

import config, {configActions} from './config/config.slice'
import * as configSelectors from './config/config.selectors'

import layers, {layersActions} from './layers/layers.slice'
import * as layersSelectors from './layers/layers.selectors'
import layersAdapter from './layers/layers.adapter'
import layersAPI from './layers/layers.api'

import popupsAdapter from './popups/popups.adapter'
import * as popupsSelectors from './popups/popups.selectors'
import popups, {popupsActions}  from './popups/popups.slice'

import controls from './controls/controls.slice'
import options from './options/options.slice'

import * as mapSelectors from './selectors'

const mapReducers = combineReducers({
	config,
	layers,
	controls,
	options,
	popups
})

export default mapReducers

export {
	mapSelectors,

	configActions,
	configSelectors,

	popupsAdapter,
	popupsSelectors,
	popupsActions,

	layersAPI,
	layersAdapter,
	layersSelectors,
	layersActions,
}