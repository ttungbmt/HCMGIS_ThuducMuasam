import {get, isEmpty, keys, includes, map, isArray} from 'lodash-es'
import template from '../utils/template';
import {useState} from "react";

let toValue = (data, attr) => {
    let value = get(data, attr)

    if(includes(attr, '.')){
        let field = attr.split('.').shift(),
            path = attr.split('.').slice(1).join('.')

        value = JSON.parse(get(data, field))

        return get(value, path, '')
    }

    return value
}

function Action({data, type, ...props}){
    if(type === 'link') {
        const {url = '/', content = 'None', ...attrs} = props
        return (<a href={template(url, data)} {...attrs} dangerouslySetInnerHTML={{__html: content}}/>)
    }

    return null
}

function PopContent({fields, heading, locs = [], data, actions}) {
    const [locIndex, setLocindex] = useState(0)
    const values = locIndex === 0 ? data : get(locs, `${locIndex}.properties`, {})

    let fieldItems = isEmpty(fields) ? keys(data).map(v => ({label: v, attribute: v})) : map(fields, v => v)

    return (
        <div className="pop-content">
            <div className="pop-heading font-bold text-primary text-blue-500 uppercase text-base">
                {template(heading, values)}
            </div>
            <div className="pop-body">
                <table className="table w-full">
                    <tbody>
                    {fieldItems.map((f, k) => (
                        <tr key={k}>
                            <td className="font-bold" style={{minWidth: 90}}>{f.label}</td>
                            <td>{toValue(values, f.attribute)}</td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            </div>
            <div className="pop-footer pt-4 pb-2 flex justify-between">
                {!isEmpty(actions) && isArray(actions) && (
                    <div className="pop-actions">
                        {actions.map((a, k) => (<div className="action-item" key={k}><Action data={values} {...a}/></div>))}
                    </div>
                )}

                {locs.length > 1 && (
                    <div className="pop-pagination flex">
                        {(locIndex > 0) && <button onClick={() => setLocindex(locIndex-1)}><i className="fal fa-long-arrow-left"/></button> }
                        <div className="px-6">{locIndex+1}/{locs.length}</div>
                        {(locIndex < locs.length-1) && <button onClick={() => setLocindex(locIndex+1)}><i className="fal fa-long-arrow-right"/></button> }
                    </div>
                )}
            </div>
        </div>
    )
}

export default PopContent