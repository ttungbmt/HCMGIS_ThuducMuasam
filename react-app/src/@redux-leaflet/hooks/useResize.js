import { useResizeDetector } from 'react-resize-detector';

export default (options = {}) => {
	const { width, height, ref } = useResizeDetector({
		refreshMode: 'debounce',
		refreshRate: 300,
		...options
	})

	return {width, height, ref }
}