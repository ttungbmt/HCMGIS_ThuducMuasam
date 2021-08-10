<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cabenh;
use Larabase\Nova\MenuItemTypes\Layer;
use Larabase\Nova\Models\MapLayer;
use Larabase\Nova\Models\MapService;
use Illuminate\Support\Facades\Http;

class MapsController extends Controller
{
    protected function toLayerType($type)
    {
        return strtolower($type);
    }

    protected function exceptFieds($popup){
        $pu = $popup->toArray();
        return array_merge($popup->toArray(), [
            'heading' => '{{phanloai_cl}}',
            'fields' => collect($pu['fields'])->whereNotIn('attribute', ['yeuto_dt', 'hoten'])->all()
        ]);
    }

    protected function formatPopup($layer)
    {
        $popup = $layer->popup;

        if (is_array($popup) && $popup['type'] === 'table' && !$popup['heading']) $popup['heading'] = $layer->name;

        if(in_array($layer->id, [16, 24, 26]) && auth()->guest()) return $this->exceptFieds($popup);

        return $popup;
    }

    protected function formatOptions($options){
        if(isset($options['tiled'])) $options['tiled'] = !($options['tiled'] === 'false');
        if(isset($options['zIndex'])) $options['zIndex'] = intval($options['zIndex']);

        return $options;
    }

    protected function getLayers()
    {
        $treeLayers = json_decode(json_encode(nova_get_menu_by_slug('map')), true);
        $menuItems = $treeLayers['menuItems'];


        $mapDeep = function ($items, $fn) {
            return collect($items)->map(function ($i, $data) use ($fn) {
                $data = [
                    'title' => $i['name']
                ];

                if ($i['type'] === 'layer-group') $data['tree'] = $i['data'];

                if ($i['type'] === 'layer') $data = array_merge([
                    'icon' => false,
                ], $data, $i['data']);

                if ($i['value'] && $i['type'] == Layer::getIdentifier()) {
                    $layer = MapLayer::find($i['value']);
                    $service = data_get($layer, 'data.service');
                    $service = MapService::find($service);

                    $options = $layer->options;
                    $options['url'] = data_get($options, 'url', optional($service)->base_url);

                    $data = array_merge($data, [
                        'control' => data_get($layer, 'data.control'),
                        'type' => $this->toLayerType($service ? $service->type : data_get($layer, 'data.type')),
                        'options' => $this->formatOptions($options),
                        'popup' => $this->formatPopup($layer),
                        'actions' => $this->formatPopup($layer),
                    ]);

                    if ($count = data_get($layer->data, 'count')) $data['count'] = (integer)$count;
                }

                if ($i['children']) $data['children'] = $fn($i['children'], $fn)->all();

                return $data;
            });
        };

        return $mapDeep($menuItems, $mapDeep);
    }

    public function builder()
    {
        return [
            'app' => [
                'name' => env('APP_NAME', 'Củ Chi Mua sắm'),
            ],
            'layers' => $this->getLayers(),
            'config' => config('nova-map.config'),
            'controls' => []
        ];
    }

    public function legend(){
        return view('api.maps.legend');
    }

    public function updateFeatureCount()
    {
        $models = MapLayer::where('data->control', 'overlay')->get()
            ->map(fn($i) => [
                'id' => $i->id,
//                'url' => $i->data['url'],
                'layers' => $i->options['layers']
            ])
            ->filter(fn($i) => $i['layers'])
        ;

        foreach ($models as $feature) {
            $request = Http::get('http://p11q3.hcmgis.vn/geoserver/wfs?service=wfs&version=2.0.0&request=GetFeature&typeNames=' . $feature['layers'] . '&&resultType=hits');
            $xml = simplexml_load_string($request->body());
            $count = intval(data_get(json_decode(json_encode($xml)), '@attributes.numberMatched'));
            $layer = MapLayer::find($feature['id']);
            if ($layer && $count) {
                $layer->data = array_merge($layer->data, ['count' => $count]);
                $layer->save();
            }
        }

        return 'OK';
    }
}
