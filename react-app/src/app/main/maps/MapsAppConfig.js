import { lazy } from 'react';
import { Redirect } from 'react-router';

const MapsAppConfig = {
	settings: {
		layout: {
			config: {}
		}
	},
	routes: [
		{
			path: '/maps/layers',
			component: lazy(() => import('./views/layers/Layers'))
		},
		{
			path: '/maps/search',
			component: lazy(() => import('./views/search/Search'))
		},
		{
			path: '/maps/stats',
			component: lazy(() => import('./views/stats/Stats'))
		},
		{
			path: '/maps/dientien-covid/detail',
			component: lazy(() => import('./views/dientienCovid/Detail'))
		},
		{
			path: '/maps/dientien-covid',
			component: lazy(() => import('./views/dientienCovid/DientienCovid')),
		},
		{
			path: '/maps/sodo-qh',
			component: lazy(() => import('./views/sodoQh/sodoQh'))
		},
		{
			path: '/maps',
			component: () => <Redirect to="/maps/layers" />
		},
	]
};

export default MapsAppConfig;
