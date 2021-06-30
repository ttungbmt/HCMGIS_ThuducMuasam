import { createSelector } from '@reduxjs/toolkit'

const selectGlobal = state => state.map.config

export const selectLoading = createSelector([selectGlobal], (config) => config.loading)
export const selectView = createSelector([selectGlobal], (config) => ({center: config.center, zoom: config.zoom}))
