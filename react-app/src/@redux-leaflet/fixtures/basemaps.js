export default {
	'none': {
		'control': 'basemap',
		'title': 'None',
		'type': 'tile',
		'options': {
			'url': '',
		}
	},
	'google': {
		'control': 'basemap',
		'title': 'Google',
		'type': 'tile',
		'options': {
			'url': 'http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',
			'subdomains': ['mt0', 'mt1', 'mt2', 'mt3'],
			'attribution': 'Map data &copy; <a href=\'https://www.google.com/maps\'>Google Maps</a>'
		}
	},
	'hcmgis': {
		'control': 'basemap',
		'title': 'HCMGIS',
		'type': 'tile',
		'options': {
			'url': 'https://maps.hcmgis.vn/geoserver/gwc/service/wmts?service=wmts&request=GetTile&version=1.0.0&layer=hcm_map:hcm_map_all&tilematrixset=EPSG:900913&tilematrix=EPSG:900913:{z}&tilerow={y}&tilecol={x}&format=image%2Fjpeg',
			'attribution': 'Map data &copy; <a href=\'https://hcmgis.vn/\'>HCMGIS</a>'
		}
	},
    'vietbando': {
        'control': 'basemap',
        'title': 'Vietbando',
        'type': 'tile',
        'options': {
            'url': 'http://images.vietbando.com/ImageLoader/GetImage.ashx?LayerIds=VBD&Y={y}&X={x}&Level={z}',
            'attribution': 'Map data &copy; <a href=\'https://vietbando.com/\'>HCMGIS</a>'
        }
    },
	'osm': {
		'control': 'basemap',
		'title': 'OpenStreetMap',
		'type': 'tile',
		'options': {
			'url': 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
			'attribution': '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
		}
	},
	'here': {
		'control': 'basemap',
		'title': 'Here',
		'type': 'tile',
		'options': {
			'url': 'https://2.{base}.maps.cit.api.here.com/maptile/2.1/{type}/newest/{scheme}/{z}/{x}/{y}/256/png?app_id={app_id}&app_code={app_code}',
			'base': 'base',
			'type': 'maptile',
			'scheme': 'normal.day',
			'app_id': 'kDm0Jq1K4Ak7Bwtn8uvk',
			'app_code': 'xnmvc4dKZrDfGlvQHXSvwQ',
			'attribution': 'Map data &copy; <a href="http://developer.here.com">HERE</a>'
		}
	},
	'bing': {
		'control': 'basemap',
		'title': 'Mapbox',
		'type': 'tile',
		'options': {
			'map': 'mapbox.streets',
			'url': 'https://api.tiles.mapbox.com/v4/{map}/{z}/{x}/{y}.png?access_token={accessToken}',
			'accessToken': 'pk.eyJ1IjoidHR1bmdibXQiLCJhIjoiY2EzNDFhZjU4ZThkNzY5NTU3M2U1YWFiNmY4OTE3OWQifQ.Bo1ss5J4UjPPOjmq9S3VQw',
			'attribution': 'Map data &copy; <a href=\'http://mapbox.com\'>Mapbox</a>'
		}
	},

	'lidar': {
		'control': 'basemap',
		'title': 'Ảnh hàng không',
		'type': 'tile',
		'options': {
			'url': 'http://trueortho.hcmgis.vn/basemap/cache_lidar/{z}/{x}/{y}.jpg',
			'attribution': 'Map data &copy; <a href=\'https://hcmgis.vn/\'>HCMGIS</a>'
		}
	},
	'google-satellite': {
		'control': 'basemap',
		'title': 'Ảnh vệ tinh',
		'type': 'tile',
		'options': {
			'url': 'http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',
			'subdomains': ['mt0', 'mt1', 'mt2', 'mt3'],
			'attribution': 'Map data &copy; <a href=\'https://www.google.com/maps\'>Google Maps</a>'
		}
	}
}