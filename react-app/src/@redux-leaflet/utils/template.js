import {toUpper, isNil} from 'lodash-es'
import Handlebars from 'handlebars'

Handlebars.registerHelper('toUpper', function (string) {
	return toUpper(string)
})

function template(string = '', data, options = {}) {
	let compiled = Handlebars.compile(isNil(string) ? '' : string, options)
	return compiled(data)
}

export default template