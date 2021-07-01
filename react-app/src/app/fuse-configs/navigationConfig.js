import i18next from 'i18next';
import en from './navigation-i18n/en';

i18next.addResourceBundle('en', 'navigation', en);

const navigationConfig = [
	{
		id: 'stats',
		title: 'Vị trí của bạn',
		type: 'group',
		icon: 'near_me_outlined',
		url: '/maps/near-by',
	},
	{
		id: 'map',
		title: 'Lớp dữ liệu',
		type: 'group',
		icon: 'layers',
		url: '/maps/layers',
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
