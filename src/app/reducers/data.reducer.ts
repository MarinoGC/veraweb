export const DATA_ALL = 'DATA_ALL';
export const DATA_ADD = 'DATA_ADD';
export const DATA_CLEAR = 'DATA_CLEAR';

export const datamd = (state, {type, payload}) => {
    switch (type) {
        case DATA_CLEAR:
            return [];
        case DATA_ADD:
            return [...state, payload];
        case DATA_ALL:
            return [payload];
        default:
            return state;
    }
};

