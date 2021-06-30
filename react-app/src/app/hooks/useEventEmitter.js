import { useRef, useEffect } from 'react';

export class EventEmitter {
    constructor() {
        this.subscriptions = new Set()
    }

    emit = (val) => {
        for (const subscription of this.subscriptions) {
            subscription(val);
        }
    };

    useSubscription = (callback) => {
        const callbackRef = useRef();
        callbackRef.current = callback;
        useEffect(() => {
            function subscription(val) {
                if (callbackRef.current) {
                    callbackRef.current(val);
                }
            }
            this.subscriptions.add(subscription);
            return () => {
                this.subscriptions.delete(subscription);
            };
        }, []);
    };
}

export default function useEventEmitter() {
    const ref = useRef();
    if (!ref.current) {
        ref.current = new EventEmitter();
    }
    return ref.current;
}