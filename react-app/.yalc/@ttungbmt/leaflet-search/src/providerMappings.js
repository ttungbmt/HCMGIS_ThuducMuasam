import {
    AlgoliaProvider,
    BingProvider,
    EsriProvider,
    HereProvider,
    LocationIQProvider,
    OpenCageProvider,
    // GoogleProvider,
    OpenStreetMapProvider
} from 'leaflet-geosearch'

import Map4DProvider from './providers/Map4DProvider'
import GoogleProvider from './providers/GoogleProvider'
import CocCocProvider from './providers/CocCocProvider'

export default {
    'algolia': AlgoliaProvider,
    'bing': BingProvider,
    'esri': EsriProvider,
    'google': GoogleProvider,
    'here': HereProvider,
    'locationiq': LocationIQProvider,
    'opencage': OpenCageProvider,
    'openstreetmap': OpenStreetMapProvider,
    'map4d': Map4DProvider,
    'cococ': CocCocProvider,
}

