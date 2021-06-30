export const selectSettings = state => state.fuse.settings.current
export const selectSidebarFolded = state => selectSettings(state).layout.config.navbar.folded
