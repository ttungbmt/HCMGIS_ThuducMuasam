import {memo, Fragment} from "react";
import IconButton from "@material-ui/core/IconButton";
import {makeStyles} from "@material-ui/core/styles";
import clsx from "clsx";
import Tooltip from "./Tooltip";
import Button from "@material-ui/core/Button";
import {isString} from 'lodash'

const useStyles = makeStyles({
    iconButton: {
        padding: 10
    },
    icon: {
        fontSize: 17,
        color: '#EA5628'
    },
});


function Menu({to, text, icon, items, children, tooltip, onClick}) {
    const classes = useStyles();

    return (
        <Fragment>
            {text ? (
                <Tooltip content={tooltip}>
                    <Button onClick={onClick}>
                        {isString(icon) ? (<i className={clsx(classes.icon, icon, 'pr-6')}/>) : icon} {text}
                    </Button>
                </Tooltip>
            ) : (
                <Tooltip content={tooltip}>
                    <IconButton onClick={onClick} size="medium" className={classes.iconButton}><i className={clsx(classes.icon, icon)} /></IconButton>
                </Tooltip>
            )}

        </Fragment>
    )
}

Menu.defaultProps = {
    onClick: () => {}
}

export default memo(Menu)