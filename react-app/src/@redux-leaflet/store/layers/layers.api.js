import { transformFlatTree, transformLayers } from '../utils'

const layersAPI = {
	async fetchAll(url, options = { method: 'GET'}) {
		const response = await fetch(url, options)
		const result = await response.json()

		let layers = transformLayers(result),
			flatLayers = transformFlatTree(layers)

		return {
			data: {
				layers,
				flatLayers
			}
		}
	}
}

export default layersAPI
