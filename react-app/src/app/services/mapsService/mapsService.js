import axios from "axios";

class MapsService {
    getBuilder = () => {
        return axios.get('/api/maps/builder').then(({data}) => data);
    };

    getLegend = () => {
        return axios.get('/api/maps/legend').then(({data}) => data);
    }
}

const instance = new MapsService();

export default instance;