import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { Store } from '@ngrx/store';

@Component({
  selector: 'app-over',
  templateUrl: './over.component.html',
  styleUrls: ['./over.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class OverComponent implements OnInit {
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
    this.localPageN = 1;
    this.localPage = this.werkmd[this.localPageN];
  }

}
