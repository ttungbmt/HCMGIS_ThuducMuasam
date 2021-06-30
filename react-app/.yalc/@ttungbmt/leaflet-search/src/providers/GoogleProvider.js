import JsonProvider from './JsonProvider';
export default class GoogleProvider extends JsonProvider {
    constructor() {
        super(...arguments);
        const {api = 'geocode', place_type = 'autocomplete'} = this.options || {}

        if(api === 'place') {
            this.options.place_type = place_type
            this.searchUrl = `https://maps.googleapis.com/maps/api/${api}/${place_type}/json`;
        } else {
            this.searchUrl = `https://maps.googleapis.com/maps/api/${api}/json`;
        }
    }

    endpoint({ query }) {
        const params = typeof query === 'string' ? { [this.getQueryType()]: query } : query;
        return this.getUrl(this.searchUrl, params);
    }

    getQueryType(){
        const {api, place_type} = this.options
        if(api === 'place'){
            if(place_type === 'textsearch') return 'query'
            else if(place_type === 'nearbysearch') return 'location'
            else if(place_type === 'details') return 'place_id'
            else if(place_type === 'photo') return 'photoreference'
            else return 'input'
        }

        return 'address';
    }

    parse(result) {
        const {api, place_type} = this.options
        let resultType = (api === 'place' && place_type === 'autocomplete') ? 'predictions' : 'results'

        return result.data[resultType].map((r) => ({
            x: r.geometry.location.lng,
            y: r.geometry.location.lat,
            label: r.formatted_address,
            bounds: [
                [r.geometry.viewport.southwest.lat, r.geometry.viewport.southwest.lng],
                [r.geometry.viewport.northeast.lat, r.geometry.viewport.northeast.lng],
            ],
            raw: r,
        }));
    }
}
//# sourceMappingURL=googleProvider.js.map