import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule, JsonpModule } from '@angular/http';

import { AppComponent } from './app.component';
import { HomeComponent} from './home/home.component';

import { StoreModule, combineReducers } from '@ngrx/store'
import { compose } from '@ngrx/core/compose';
import { StoreDevtoolsModule } from '@ngrx/store-devtools';
import { localStorageSync } from "ngrx-store-localstorage";

import { extraNav } from './reducers/extra.reducer';
import { werkmd } from './reducers/werk.reducer';
import { datamd } from './reducers/data.reducer';
import { OverComponent } from './over/over.component';
import { ContactComponent } from './contact/contact.component';
import { StartComponent } from './start/start.component';

@NgModule({
    declarations: [
        AppComponent,
        HomeComponent,
        OverComponent,
        ContactComponent,
        StartComponent,
     ],
    imports: [
        BrowserModule,
        FormsModule,
        HttpModule,
        JsonpModule,
        StoreModule.provideStore(
            compose(
                localStorageSync(['extraNav'], true),
                combineReducers
            ) ({extraNav, werkmd, datamd})
        ),
        StoreDevtoolsModule.instrumentOnlyWithExtension()
    ],
    providers: [],
    bootstrap: [AppComponent]
})
export class AppModule { }
