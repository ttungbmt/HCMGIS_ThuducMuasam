import * as moment from 'moment/moment.js'
import 'moment/locale/vi.js'

// moment(k, 'DD/MM/YYYY').unix()
// moment.unix(k).format("DD/MM/YYYY")
// .add(5, 'd')

export const toUnixDate = (str, dateFormat = 'DD/MM/YYYY') => moment(str, dateFormat).unix()

export const unixDate = () => moment().unix()
export const today = (dateFormat = 'DD/MM/YYYY') => moment().format(dateFormat)

export default moment