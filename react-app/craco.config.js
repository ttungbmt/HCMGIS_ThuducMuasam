const {addWebpackExternals, addBundleVisualizer } = require('customize-cra')
// const {BundleAnalyzerPlugin} = require('webpack-bundle-analyzer')

module.exports = {
	style: {
		postcss: {
			plugins: [require('tailwindcss'), require('autoprefixer')]
		}
	},
	webpack: {
		configure: (config, { env, paths }) => {
			const isProductionBuild = process.env.NODE_ENV === 'production'
			// const analyzerMode = process.env.REACT_APP_INTERACTIVE_ANALYZE ? 'server' : 'json'

			if (isProductionBuild) {
				// plugins.push(new BundleAnalyzerPlugin({ analyzerMode }))
				addBundleVisualizer()(config)
			}

			addWebpackExternals({
				'jquery': '$',
				'lodash': '_',
				'lodash-es': '_',
				'leaflet': 'L',
				'charts.js': 'Chart',
				'@amcharts/amcharts4/core': 'am4core',
				'@amcharts/amcharts4/themes/animated': 'am4themes_animated',
				'@amcharts/amcharts4/charts': 'am4charts',
			})(config)

			return config;
		}
	}
};
