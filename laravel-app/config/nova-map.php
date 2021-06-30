<?php

return [
    'config' => [
        'center' => [10.8209134762129, 106.78882598876955],
        'zoom' => 13,
        'zoomControl' => false
    ],
    'layers' => [
        [
            'control' => 'basemap',
            'type' => 'tile',
            'title' => 'Google',
            'options' => ['url' => 'http://mt2.google.com/vt/lyrs=m&x={x}&y={y}&z={z}'],
            'active' => true,
        ],
        [
            'control' => 'basemap',
            'type' => 'tile',
            'title' => 'Thủ Đức Maps',
            'options' => ['url' => 'http://thuduc-maps.hcmgis.vn//thuducserver/gwc/service/wmts?layer=thuduc:thuduc_maps&style=&tilematrixset=EPSG:900913&Service=WMTS&Request=GetTile&Version=1.0.0&Format=image/png&TileMatrix=EPSG:900913:{z}&TileCol={x}&TileRow={y}'],
        ],
        [
            'control' => 'basemap',
            'type' => 'tile',
            'title' => 'Vietbando',
            'options' => ['url' => 'http://images.vietbando.com/ImageLoader/GetImage.ashx?Ver=2016&LayerIds=VBD&Level={z}&X={x}&Y={y}'],
        ],
    ],
    'search' => [
        'provider' => 'google',
        'key' => 'AIzaSyB3ESY9BEuxjnPCKnfno00FCG61f7Bes-g',
        'options' => [
            'api' => 'place',
            'place_type' => 'textsearch',
            'cors' => env('APP_URL').'/api/cors?url='
        ],
        'params' => [
            'region' => 'vn',
            'language' => 'vi',
            'location' => '10.8209134762129,106.78882598876955',
            'radius' => 1e5,
//            'bounds' => '10.741743087768555,106.69782257080078|10.898930549621582,106.88191223144531'

        ]
    ]
];
