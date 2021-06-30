import {useHistory} from 'react-router';
import {useMount} from 'react-use';

function useBack(url, isBack) {
    const history = useHistory();

    useMount(() => {
        if(isBack) history.push(url)
    })
}

export default useBack