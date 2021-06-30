import i18next from 'i18next';
import en from './navigation-i18n/en';

i18next.addResourceBundle('en', 'navigation', en);

const navigationConfig = [
	{
		id: 'map',
		title: 'Lớp dữ liệu',
		type: 'group',
		icon: 'layers',
		url: '/maps/layers',
	},
	{
		id: 'search',
		title: 'Tìm kiếm',
		type: 'group',
		icon: 'search',
		url: '/maps/search',
	},
	{
		id: 'dientien-covid',
		title: 'Diễn tiến Covid',
		type: 'group',
		icon: 'timeline',
		url: '/maps/dientien-covid',
	},
	{
		id: 'sodo-qh',
		title: 'Sơ đồ quan hệ',
		type: 'group',
		icon: 'people',
		url: '/maps/sodo-qh',
	},
	{
		id: 'stats',
		title: 'Thống kê',
		type: 'group',
		icon: 'assessment',
		url: '/maps/stats',
	},
	{
		id: 'portal',
		title: 'Quản trị hệ thống',
		translate: 'ADMIN',
		type: 'group',
		icon: 'web',
		href: '/nova',
	},
];

export default navigationConfig;
