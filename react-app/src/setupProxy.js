const {createProxyMiddleware} = require('http-proxy-middleware');

module.exports = function (app) {
    app.use(
        '/api',
        createProxyMiddleware({
            target: 'http://thuduc-muasam.local:88',
            changeOrigin: true,
        })
    );

    app.use(
        '/geoserver',
        createProxyMiddleware({
            target: 'http://192.168.1.242:8085',
            changeOrigin: true
        })
    );
};