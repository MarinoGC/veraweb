import { Injectable } from '@angular/core';
import {Http, Jsonp, Response} from '@angular/http';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/do';
import 'rxjs/add/operator/debounceTime';
import 'rxjs/add/operator/distinctUntilChanged';
import 'rxjs/add/operator/switchMap';
import { Observable } from 'rxjs/Observable';

@Injectable()
export class HttpService {

    constructor(private http: Http,
                private jsonp: Jsonp) {
    }

    readDatas(URL) {
        console.log(URL);
        return this.http.get(URL)
            .map(data => data.json())
            .catch(this.handleError);
    }

    private handleError(error: Response) {
        console.error('MGC-error: ' + error);
        return Observable.throw(error.json().error || 'Server error');
    }
}
