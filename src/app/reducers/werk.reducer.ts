export const WERK_ALL = 'WERK_ALL';
export const WERK_ADD = 'WERK_ADD';
export const WERK_CLEAR = 'WERK_CLEAR';

export const werkmd = (state, {type, payload}) => {
    switch (type) {
        case WERK_CLEAR:
            return [];
        case WERK_ADD:
            return [...state, payload];
        case WERK_ALL:
            return [payload];
        default:
            return state;
    }
};

