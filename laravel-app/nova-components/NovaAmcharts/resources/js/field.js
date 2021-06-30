Nova.booting((Vue, router, store) => {
  Vue.component('index-nova-amcharts', require('./components/IndexField'))
  Vue.component('detail-nova-amcharts', require('./components/DetailField'))
  Vue.component('form-nova-amcharts', require('./components/FormField'))
})
