export const EXTRA_NAV = 'EXTRA_NAV';

export const extraNav = (state, {type, payload}) => {
    switch (type) {
        case EXTRA_NAV:
            return [payload];
        default:
            return state;
    }
};