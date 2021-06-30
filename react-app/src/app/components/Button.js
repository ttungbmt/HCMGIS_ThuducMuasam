import Tooltip from "./Tooltip";
import {IconButton, makeStyles} from "@material-ui/core";
import React, {memo} from "react";

const useStyles = makeStyles((theme) => ({
    margin: {
        padding: 7,
    }
}))

function Button({tooltip, onClick, icon}) {
    const classes = useStyles();

    return (
        <Tooltip content={tooltip}>
            <IconButton className={classes.margin} onClick={onClick}>
                {icon}
            </IconButton>
        </Tooltip>
    )
}
export default memo(Button)