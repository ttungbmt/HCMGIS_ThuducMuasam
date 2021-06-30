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
	// {
	// 	id: 'search',
	// 	title: 'Tìm kiếm',
	// 	type: 'group',
	// 	icon: 'search',
	// 	url: '/maps/search',
	// },
	{
		id: 'stats',
		title: 'Tìm kiếm lân cận',
		type: 'group',
		icon: 'near_me_outlined',
		url: '/maps/near-by',
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
