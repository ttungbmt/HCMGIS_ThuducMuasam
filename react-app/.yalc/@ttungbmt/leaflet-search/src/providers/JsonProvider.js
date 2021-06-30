import {JsonProvider as BaseJsonProvider} from 'leaflet-geosearch'
import {isString} from "lodash-es";

class JsonProvider extends BaseJsonProvider {
    getUrl(url, params) {
        let {cors = ''} = this.options
        cors = isString(cors) ? cors : (cors ? 'https://cors-anywhere.herokuapp.com' : false)
        return `${cors}${url}?${this.getParamString(params)}`;
    }
}

export default JsonProvider