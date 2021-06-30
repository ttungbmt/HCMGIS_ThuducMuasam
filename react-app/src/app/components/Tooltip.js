import React, {memo} from 'react'
import Tippy from '@tippyjs/react';
import 'tippy.js/dist/tippy.css';
import 'tippy.js/animations/scale.css';
import 'tippy.js/animations/scale-subtle.css';
import 'tippy.js/animations/scale-extreme.css';

function Tooltip({content, children, ...rest}){
    if(!content) return children

    return (
        <Tippy animation="scale" content={content} {...rest}>
            {children}
        </Tippy>
    )
}

export default memo(Tooltip)