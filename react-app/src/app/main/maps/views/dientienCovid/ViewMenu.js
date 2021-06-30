import React, {useState} from 'react';
import {MenuItem, Menu, Button, IconButton, makeStyles} from '@material-ui/core';
import ListIcon from '@material-ui/icons/List';

const useStyles = makeStyles((theme) => ({
    margin: {
        padding: 7,
    }
}))

export default function ViewMenu({items = [], selected = 0}) {
    const classes = useStyles();
    const [selectedIndex, setSelectedIndex] = useState(selected);
    const [anchorEl, setAnchorEl] = useState(null);

    const handleClick = (event) => setAnchorEl(event.currentTarget);
    const handleClose = () => setAnchorEl(null);
    const handleMenuItemClick = (e, index) => {
        setSelectedIndex(index);
        setAnchorEl(null);
        if(items[index].onClick) items[index].onClick()
    };

    return (
        <>
            <IconButton onClick={handleClick} className={classes.margin} >
                <ListIcon/>
            </IconButton>
            <Menu
                anchorEl={anchorEl}
                keepMounted
                open={Boolean(anchorEl)}
                onClose={handleClose}
            >
                {items.map(({label}, index ) => (
                    <MenuItem
                        key={index}
                        selected={index === selectedIndex}
                        onClick={e => handleMenuItemClick(e, index)}>{label}</MenuItem>
                ))}
            </Menu>
        </>
    );
}