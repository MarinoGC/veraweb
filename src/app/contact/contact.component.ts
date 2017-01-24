import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { Store } from '@ngrx/store';

@Component({
    selector: 'app-contact',
    templateUrl: './contact.component.html',
    styleUrls: ['./contact.component.css'],
    encapsulation: ViewEncapsulation.None
})
export class ContactComponent implements OnInit {
    werkmd;

    private localPage: any;
    private localPageN: number;

    constructor(private store: Store<any>) {
        store.select('werkmd')
            .subscribe(werkmd => {
                this.werkmd = werkmd;
            })
    }

    ngOnInit() {
        this.localPageN = 2;
        this.localPage = this.werkmd[this.localPageN];
    }

}
