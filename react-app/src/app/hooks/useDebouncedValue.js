import React, {useState} from 'react';
import {useDebounce} from 'react-use';
import {defaultTo} from 'lodash-es';

function useDebouncedValue(initialValue = '', options = {}) {
    const [searchTerm, setSearchTerm] = useState(initialValue)
    const [debouncedSearchTerm, setDebouncedSearchTerm] = React.useState(initialValue);

    const [, cancel] = useDebounce(
        () => setDebouncedSearchTerm(searchTerm),
        defaultTo(options.delay, 300),
        [searchTerm]
    );
    return [debouncedSearchTerm, setSearchTerm, cancel]
}

export default useDebouncedValue