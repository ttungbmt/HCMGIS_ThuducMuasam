import JsonProvider from "./JsonProvider";

class Map4DProvider extends JsonProvider {
    endpoint({ query, type }) {
        const params = {
            ...this.options.params,
            text: query
        }

        return this.getUrl('https://api.map4d.vn/map/autosuggest', params);
    }


    parse({ data }) {
        if(data.code !== 'ok') return [];

        return data.result.map((r) => ({
            x: r.location.lng,
            y: r.location.lat,
            label: r.address,
            bounds: null, // Map4D API does not provide bounds
            raw: r
        }));
    }
}

export default Map4DProvider
