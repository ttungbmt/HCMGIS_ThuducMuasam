import JsonProvider from "./JsonProvider";

class CocCocProvider extends JsonProvider {
    endpoint({query}) {
        const url = 'https://map.coccoc.com/map/search.json',
            params = {
                suggestions: true,
                pos_accuracy: 1504,
                ...this.options.params,
                query
            }

        return this.getUrl('https://map.coccoc.com/map/search.json', params);
    }

    parse({data}) {
        if (!data.result) return [];

        return data.result.poi.map((r) => ({
            x: r.gps.longitude,
            y: r.gps.latitude,
            label: r.title,
            bounds: null, // CocCoc API does not provide bounds
            raw: r
        }));
    }
}

export default CocCocProvider
