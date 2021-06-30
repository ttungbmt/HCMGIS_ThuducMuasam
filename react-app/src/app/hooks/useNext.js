import {useHistory} from 'react-router';
import {useMount} from 'react-use';

function useNext(url, isNext) {
    const history = useHistory();

    useMount(() => {
        if(isNext) history.push(url)
    })
}

export default useNext