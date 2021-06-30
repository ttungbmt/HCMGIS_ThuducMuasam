import { cloneDeep, set } from 'lodash';
import { setDefaultSettings } from 'app/store/fuse/settingsSlice';
import { selectSidebarFolded, selectSettings } from './selectors';

export const sidebarToggle = () => (dispatch, getState) => {
	const state = getState()
	const settings = cloneDeep(selectSettings(state))
	const folded = selectSidebarFolded(state)
	set(settings, 'layout.config.navbar.folded', !folded)
	dispatch(setDefaultSettings(settings))
}

