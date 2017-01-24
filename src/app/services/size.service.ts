import { Injectable } from '@angular/core';
import {Observable, BehaviorSubject} from 'rxjs';

@Injectable()
export class SizeService {
    width$: Observable<number>;
//    height$: Observable<number>;

    constructor() {
        let windowSize$ = new BehaviorSubject(getWindowSize());
        this.width$ = (windowSize$.pluck('width') as Observable<number>).distinctUntilChanged();
//        this.height$ = (windowSize$.pluck('height') as Observable<number>).distinctUntilChanged();

        Observable.fromEvent(window, 'resize')
            .map(getWindowSize)
            .subscribe(windowSize$);
    }
}

function getWindowSize() {
    return {
        width: window.innerWidth
//        height: window.innerHeight,
    };
}
