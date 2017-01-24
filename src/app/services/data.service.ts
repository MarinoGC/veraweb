import { Injectable } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';

@Injectable()
export class DataService {

    constructor(private sanitizer: DomSanitizer) {
    }

    private werk1: any[];
    private werk2: any[][];
    private v: number = 0;
    private w: number = 0;
    private wt: number = 0;
    private wl: number = 0;
    private werkTot: any[];
    private werkPlus: any[];
    private werkItem: any;
    private nrVelden: number = 10;  //het aantal velden t.b.v. initializatie (niet te weinig, teveel hindert niet)
    private nrPag: number = 3;      //idem aantal pagina's

    addSecurity(value) {
        this.werkPlus = [];
        this.wl = value.length;
        for (this.w = 0; this.w < this.wl; this.w++) {
            this.werkItem = {
                'secure': this.sanitizer.bypassSecurityTrustHtml(value[this.w]['content2']),
                'content1': value[this.w]['content1'],
                'content2': value[this.w]['content2'],
                'pag': value[this.w]['pag'],
                'veld': value[this.w]['veld'],
                'info': (value[this.w]['content1'].length > 0)
            };
            this.werkPlus.push(this.werkItem);
        }
        return this.werkPlus;
    }

    initieerStore(value) {
        this.werkTot = [];
        for (this.v = 0; this.v < this.nrPag; this.v++) {
            this.werkPlus = [];
            for (this.w = 0; this.w < this.nrVelden; this.w++) {
                this.werkItem = {
                    'secure': this.sanitizer.bypassSecurityTrustHtml(value),
                    'content1': value,
                    'content2': value,
                    'pag': this.v,
                    'veld': this.w
                };
                this.werkPlus.push(this.werkItem);
            }
            this.werkTot.push(this.werkPlus);
        }
        return this.werkTot;
    }
}
