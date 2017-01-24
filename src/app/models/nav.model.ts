export interface ItemType {
    name:string;
    id:number;
    data:{};
}

export const NavItems = [
    { name: 'home',    id: 0, data: {
        name: 'HOME',
        nav: 'nav0',
        veldA: 'veld0A',
        veldB: 'veld0B',
        vis: [true, false, false]}
    },
    { name: 'over',    id: 1, data: {
        name: 'OVER',
        nav: 'nav1',
        veldA: 'veld1A',
        veldB: 'veld1B',
        vis: [false, true, false]}
    },
    { name: 'contact',    id: 2, data: {
        name: 'CONTACT',
        nav: 'nav2',
        veldA: 'veld2A',
        veldB: 'veld2B',
        vis: [false, false, true]}
    },
];


