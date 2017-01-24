export const EnviConstant = {
    versie: "versie_4",
//    urlData: "http://zeiltocht.eu/",
//    urlData: "http://dev2.dhs.org/",
//    urlData: "http://carasso.biz/ingrid/",
    urlData: "./",
    urlFirebase: "https://angdev-6b59d.firebaseio.com/data.json"
    };

export const PHPparam = {
    max: 5000000,
    resH: 640,
    resL: 120
}

export interface EnviType {
    versie: string,
    urlData: string,
    urlFirebase: string
}

export const Cats = [
        {
            id: 0,
            name: 'Avignon',
            nav: 'AVIGNON',
            closed: false
        },
        {
            id: 1,
            name: 'Rome',
            nav: 'ROME',
            closed: false
        },
        {
            id: 2,
            name: 'Test',
            nav: 'TEST',
            closed: true
        },
        {
            id: 3,
            name: 'Thuis',
            nav: 'THUIS',
            closed: true
        }
    ];

