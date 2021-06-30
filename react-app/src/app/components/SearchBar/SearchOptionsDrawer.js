import Drawer from "@material-ui/core/Drawer";
import React from "react";
import {useSearchBarContext} from "./SearchBarContext";
import {makeStyles} from "@material-ui/core/styles";
import FormControl from "@material-ui/core/FormControl";
import FormLabel from "@material-ui/core/FormLabel";
import RadioGroup from "@material-ui/core/RadioGroup";
import Radio from "@material-ui/core/Radio";
import FormControlLabel from "@material-ui/core/FormControlLabel";
import {filter} from 'lodash-es'

const useStyles = makeStyles({
    root: {},
    container: {
        width: 250,
        padding: 20
    }
});

const sourceItems = [
    {group: 'service', label: 'Google', value: 'google'},
    {group: 'service', label: 'OSM', value: 'osm'},
    {group: 'service', label: 'HCMGIS', value: 'hcmgis'},
    {group: 'service', label: 'Cốc cốc', value: 'coccoc'},
    {group: 'layer', label: 'Tất cả', value: 'all'},
    {group: 'layer', label: 'ATM', value: 'atm'},
]

function SearchOptionsDrawer() {
    const classes = useStyles();
    const {drawer, toggleDrawer, source, setSource} = useSearchBarContext()

    const onChange = e => setSource(e.target.value)

    return (
        <Drawer open={drawer} onClose={e => toggleDrawer(false)}>
            <div className={classes.container}>
                <FormControl component="fieldset">
                    <RadioGroup name="source" value={source.value} onChange={onChange}>
                        <FormLabel component="legend">Dịch vụ bản đồ</FormLabel>
                        {filter(sourceItems, {group: 'service'}).map(({value, label}, k) => (
                            <FormControlLabel key={k} value={value} control={<Radio/>} label={label}/>
                        ))}
                        <FormLabel component="legend">Lớp dữ liệu</FormLabel>
                        {filter(sourceItems, {group: 'layer'}).map(({value, label}, k) => (
                            <FormControlLabel key={k} value={value} control={<Radio/>} label={label}/>
                        ))}
                    </RadioGroup>
                </FormControl>
            </div>
        </Drawer>
    )
}

export default SearchOptionsDrawer