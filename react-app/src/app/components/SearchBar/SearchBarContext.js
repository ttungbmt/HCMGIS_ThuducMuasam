import {useImmer} from "use-immer";
import {useDebounce, useUpdateEffect} from "react-use";
import {useCallback, useMemo} from "react";
import {defaultTo} from "lodash";
import constate from "constate";
import {nanoid} from "nanoid";

function useSearchBar() {
    const [search, setSearch] = useImmer({
        text: '',
        debouncedText: '',
        selected: null,
        drawer: false,
        loading: false,
        source: {
            value: 'google',
            items: []
        },
        suggest: {
            selected: null,
            items: []
        },
        list: {
            request: {},
            selected: null,
            items: []
        },
        detail: {

        }
    });
    const [, cancel] = useDebounce(
        () => setSearch(state => void (state.debouncedText = search.text)),
        350,
        [search.text]
    );

    const setText = useCallback((text) => setSearch(state => void (state.text = defaultTo(text, ''))), []);
    const setSuggestSelected = useCallback((selected) => setSearch(state => void (state.suggest.selected = selected)), []);
    const setSuggestItems = useCallback((items) => setSearch(state => void (state.suggest.items = items.map(r => ({id: nanoid(), ...r})))), []);
    const toggleDrawer = useCallback((open) => setSearch(state => void (state.drawer = open)), []);
    const setSource = useCallback((value) => setSearch(state => void (state.source.value = value)), []);
    const setLoading = useCallback((loading) => setSearch(state => void (state.loading = loading)), []);
    const setSourceItems = useCallback((items) => setSearch(state => void (state.source.items = items)), []);

    const selectedItem = useMemo(() => search.selected ? search.suggestions[search.selected] : null, [search.selected])

    return {
        ...search,
        selectedItem,
        suggestItems: search.suggest.items,
        setText, setSuggestSelected, setSuggestItems, toggleDrawer, setSource, setLoading
    };
}

export const [SearchBarProvider,  useSearchBarContext] = constate(useSearchBar)