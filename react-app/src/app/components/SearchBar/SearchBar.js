import React, {memo, useCallback, useEffect, useState} from 'react';
import PropTypes from 'prop-types';
import Downshift, {useCombobox} from 'downshift';
import TextField from '@material-ui/core/TextField';
import Paper from '@material-ui/core/Paper';
import MenuItem from '@material-ui/core/MenuItem';
import {makeStyles} from "@material-ui/core/styles";
import {deburr, includes, get, isNil, findIndex, throttle, debounce} from "lodash";
import Icon from "@material-ui/core/Icon";
import IconButton from "@material-ui/core/IconButton";
import SearchOptionsDrawer from "./SearchOptionsDrawer";
import {useSearchBarContext} from "./SearchBarContext";
import {CocCocProvider} from '@ttungbmt/leaflet-search'
import {useUpdateEffect} from 'react-use';
import CircularProgress from "@material-ui/core/CircularProgress";
import {nanoid} from "nanoid";
import {matchSorter} from "match-sorter";
import { stripHtml } from "string-strip-html";

const useStyles = makeStyles({
    root: {},
    endAdornment: {},
});

function renderInput(inputProps) {
    const {InputProps, classes, ref, ...other} = inputProps;

    return (
        <TextField
            InputProps={{
                inputRef: ref,
                classes: {
                    root: classes.inputRoot,
                    input: classes.inputInput,
                },
                ...InputProps,
            }}
            {...other}
        />
    );
}

function renderSuggestion({suggestion, index, itemProps, highlightedIndex, selectedItem}) {
    const isHighlighted = highlightedIndex === index;
    const isSelected = (selectedItem || '').indexOf(suggestion.label) > -1;

    return (
        <MenuItem
            {...itemProps}
            key={suggestion.label}
            selected={isHighlighted}
            component="div"
            style={{
                fontWeight: isSelected ? 500 : 400,
            }}
        >
            {suggestion.label}
        </MenuItem>
    );
}

renderSuggestion.propTypes = {
    highlightedIndex: PropTypes.number,
    index: PropTypes.number,
    itemProps: PropTypes.object,
    selectedItem: PropTypes.string,
    suggestion: PropTypes.shape({label: PropTypes.string}).isRequired,
};

function getSuggestions(suggestItems, value) {
    const inputValue = deburr(value.trim()).toLowerCase();
    const inputLength = inputValue.length;
    let count = 0;

    return inputLength === 0
        ? []
        : suggestItems
    // : suggestItems.filter(suggestion => {
    //     const keep =
    //         count < 5 && suggestion.label.slice(0, inputLength).toLowerCase() === inputValue;
    //
    //     if (keep) {
    //         count += 1;
    //     }
    //
    //     return keep;
    // });
}

function useSearchAPIProvider() {
    const {setSuggestItems, setLoading} = useSearchBarContext()

    return useCallback(debounce(async (text) => {
        setLoading(true)
        const provider = new CocCocProvider({cors: 'https://thuduc-maps.hcmgis.vn/api/cors?url='})
        const results = await provider.search({query: text});
        setSuggestItems(results)
        setLoading(false)
    }, 300), [])
}

function Input(props) {
    return <input type="text" {...props}/>
}

function SearchBar(props) {
    const classes = useStyles();
    const {text, toggleDrawer, suggestItems, setText, loading, setSuggestItems, setSuggestSelected} = useSearchBarContext()
    const fetchQuery = useSearchAPIProvider()

    const {
        isOpen,
        getMenuProps,
        getInputProps,
        getComboboxProps,
        highlightedIndex,
        getItemProps,
        openMenu,
        reset,
    } = useCombobox({
        items: suggestItems,
        itemToString: item => (item ? stripHtml(item.label).result : ''),
        onStateChange: ({type, highlightedIndex, selectedItem, ...state}) => {
            switch (type) {
                case '__item_click__':
                case '__input_keydown_enter__':
                    let index = findIndex(suggestItems, {id: selectedItem.id})
                    setSuggestSelected(index)
                    break
                case '__function_reset__':
                    setSuggestSelected(null)
                    setSuggestItems([])
                    setText('')
                    break
                case '__input_change__':
                    setSuggestSelected(null)
                    break
                case '__input_keydown_arrow_down__':
                case '__input_keydown_arrow_up__':
                    setSuggestSelected(highlightedIndex)
                    break

            }
        },
    })


    const onFocus = () => {
        openMenu()
    }



    const onKeyUp = e => {
        let value = e.target.value
        setSuggestSelected(null)
        setText(value)
        fetchQuery(value)
    }

    return (
        <div>
            <div {...getComboboxProps()}>
                {renderInput({
                    fullWidth: true,
                    classes,
                    InputProps: getInputProps({
                        placeholder: 'Tìm kiếm...',
                        onFocus: onFocus,
                        onKeyUp: onKeyUp,
                        endAdornment: (
                            <div className="flex items-center">
                                {text && (loading ? (
                                    <CircularProgress size={17}/>
                                ) : (
                                    <IconButton size="small" onClick={e => reset()}><Icon
                                        fontSize="small">close</Icon></IconButton>
                                ))}
                                <IconButton size="small" onClick={e => toggleDrawer(true)}><Icon
                                    fontSize="small">layers</Icon></IconButton>
                            </div>
                        )
                    }),
                })}
            </div>
            <div {...getMenuProps()}>
                {isOpen && suggestItems.map((item, index) => (
                    <div
                        style={
                            highlightedIndex === index
                                ? { backgroundColor: '#bde4ff' }
                                : {}
                        }
                        key={`${item}${index}`}
                        {...getItemProps({ item, index })}
                    >
                        <span dangerouslySetInnerHTML={{__html: item.label}} />
                    </div>
                ))}
            </div>
        </div>
    )


    //
    // const onInputValueChange = (inputValue, helpers) => {
    //     setText(inputValue)
    //
    //     // setTimeout(() => {
    //     //     console.log(helpers)
    //     //     helpers.reset({
    //     //         selectedItem: {value: inputValue}
    //     //     })
    //     // }, 2000)
    //     // console.log(inputValue, setSuggestItems)
    //     // if(inputValue === '') setSuggestItems([])
    // }
    //
    // const onSelect = (selectedItem) => {
    //     console.log(selectedItem)
    // }
    //
    // // const onStateChange = (state) => {
    // //     console.log(state)
    // // }
    //
    //
    // return (
    //     <Downshift
    //         // inputValue={text}
    //         // selectedItem={{value: text}}
    //         onOuterClick={({setState}) => {
    //             // setState(state => ({...state, selectedItem: {value: ''}}))
    //         }}
    //         onChange={selection => {
    //             console.log(selection)
    //         }}
    //         // onStateChange={onStateChange}
    //         onInputValueChange={onInputValueChange}
    //         itemToString={item => (item ? item.value : '')}
    //     >
    //         {({
    //               getInputProps,
    //               getItemProps,
    //               getLabelProps,
    //               getMenuProps,
    //               isOpen,
    //               inputValue,
    //               highlightedIndex,
    //               selectedItem,
    //               getRootProps,
    //               openMenu
    //           }) => {
    //             const filteredItems = matchSorter(suggestItems, inputValue, {
    //                 keys: ['value'],
    //                 maxRanking: matchSorter.rankings.STARTS_WITH
    //             })
    //
    //             const isOpenMenu = (isOpen = false) => (isOpen && !!filteredItems.length)
    //
    //             return (
    //                 <div className="downshift">
    //                     <div {...getRootProps({}, {suppressRefError: true})}>
    //                         <Input {...getInputProps()} onFocus={() => {
    //                             isOpenMenu && openMenu(true)
    //                         }}/>
    //                     </div>
    //                     <div {...getMenuProps()}>
    //                         {isOpenMenu(isOpen)
    //                             ? filteredItems.map((item, index) => (
    //                                 <div
    //                                     {...getItemProps({
    //                                         key: item.value,
    //                                         index,
    //                                         item,
    //                                         style: {
    //                                             backgroundColor:
    //                                                 highlightedIndex === index ? 'lightgray' : 'white',
    //                                             fontWeight: selectedItem === item ? 'bold' : 'normal',
    //                                         },
    //                                     })}
    //                                 >
    //                                     {item.value}
    //                                 </div>
    //                             ))
    //                             : null}
    //                     </div>
    //                 </div>
    //             )
    //         }}
    //     </Downshift>
    // )
    // const {
    //     // autoComplete = false,
    //     // autoHighlight = false,
    //     // autoSelect = false,
    //     // blurOnSelect = false,
    //     // ChipProps,
    //     // className,
    //     // clearIcon = <ClearIcon fontSize="small" />,
    //     // clearOnBlur = !props.freeSolo,
    //     // clearOnEscape = false,
    //     // clearText = 'Clear',
    //     // closeText = 'Close',
    //     // defaultValue = props.multiple ? [] : null,
    //     // disableClearable = false,
    //     // disableCloseOnSelect = false,
    //     // disabled = false,
    //     // disabledItemsFocusable = false,
    //     // disableListWrap = false,
    //     // disablePortal = false,
    //     // filterOptions,
    //     // filterSelectedOptions = false,
    //     // forcePopupIcon = 'auto',
    //     // freeSolo = false,
    //     // fullWidth = false,
    //     // getLimitTagsText = (more) => `+${more}`,
    //     // getOptionDisabled,
    //     // getOptionLabel = (option) => option.label ?? option,
    //     // getOptionSelected,
    //     // groupBy,
    //     // handleHomeEndKeys = !props.freeSolo,
    //     // id: idProp,
    //     // includeInputInList = false,
    //     // inputValue: inputValueProp,
    //     // limitTags = -1,
    //     // ListboxComponent = 'ul',
    //     // ListboxProps,
    //     // loading = false,
    //     // loadingText = 'Loading…',
    //     // multiple = false,
    //     // noOptionsText = 'No options',
    //     // onChange,
    //     // onClose,
    //     // onHighlightChange,
    //     // onInputChange,
    //     // onOpen,
    //     // open,
    //     // openOnFocus = false,
    //     // openText = 'Open',
    //     // options,
    //     // PaperComponent = Paper,
    //     // PopperComponent = Popper,
    //     // popupIcon = <ArrowDropDownIcon />,
    //     // renderGroup: renderGroupProp,
    //     // renderInput,
    //     // renderOption: renderOptionProp,
    //     // renderTags,
    //     // selectOnFocus = !props.freeSolo,
    //     // size = 'medium',
    //     // value: valueProp,
    //     ...other
    // } = props;
    //
    // const [selectedItem, setSelectedItem] = useState([])
    //
    // useSearchAPIProvider()
    //
    // const {text, toggleDrawer, suggestItems, setText, loading} = useSearchBarContext()
    //
    // const handleInputChange = e => setText(e.target.value)
    //
    // const handleChange = item => {
    //     console.log(item)
    //     // setText(item)
    //     // setSelectedItem(selectedItem.indexOf(item) === -1 ? [...selectedItem, item] : selectedItem)
    // };
    //
    // const handleSelect = (selectedItem) => {
    //     console.log(selectedItem)
    // }
    //
    //
    //
    // return (
    //     <Downshift
    //         inputValue={text}
    //         onChange={handleChange}
    //         onSelect={handleSelect}
    //         selectedItem={selectedItem}
    //     >
    //         {({
    //               getInputProps,
    //               getItemProps,
    //               getMenuProps,
    //               highlightedIndex,
    //               inputValue,
    //               isOpen,
    //               selectedItem,
    //           }) => (
    //             <div className={classes.container}>
    //                 {renderInput({
    //                     fullWidth: true,
    //                     classes,
    //                     InputProps: getInputProps({
    //                         placeholder: 'Tìm kiếm...',
    //                         onChange: handleInputChange,
    //                         endAdornment: (
    //                             <div className="flex items-center">
    //                                 {text && (loading ? (
    //                                     <CircularProgress size={17}/>
    //                                 ) : (
    //                                     <IconButton size="small" onClick={e => handleChange('')}><Icon
    //                                         fontSize="small">close</Icon></IconButton>
    //                                 ))}
    //                                 <IconButton size="small" onClick={e => toggleDrawer(true)}><Icon
    //                                     fontSize="small">layers</Icon></IconButton>
    //                             </div>
    //                         )
    //                     }),
    //                 })}
    //                 <div {...getMenuProps()}>
    //                     {(isOpen && !loading ) ? (
    //                         <Paper className={classes.paper} square>
    //                             {getSuggestions(suggestItems, inputValue).map((suggestion, index) =>
    //                                 renderSuggestion({
    //                                     suggestion,
    //                                     index,
    //                                     itemProps: getItemProps({item: suggestion.label}),
    //                                     highlightedIndex,
    //                                     selectedItem,
    //                                 }),
    //                             )}
    //                         </Paper>
    //                     ) : null}
    //                 </div>
    //                 <SearchOptionsDrawer/>
    //             </div>
    //         )}
    //     </Downshift>
    // );
}

SearchBar.propTypes = {
    /**
     * This prop is used to help implement the accessibility logic.
     * If you don't provide this prop. It falls back to a randomly generated id.
     */
    id: PropTypes.string,
    /**
     * Override or extend the styles applied to the component.
     */
    classes: PropTypes.object,
    /**
     * @ignore
     */
    className: PropTypes.string,
    /**
     * The icon to display in place of the default clear icon.
     * @default <ClearIcon fontSize="small" />
     */
    clearIcon: PropTypes.node,
};


export default memo(SearchBar)