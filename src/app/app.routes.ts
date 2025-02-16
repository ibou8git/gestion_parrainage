app.routes.ts

import { Routes } from '@angular/router';
import { ImportElecteurComponent } from './components/import-electeurs/import-electeurs.component';
import { GestionCandidatsComponent } from './components/gestion-candidat/gestion-candidat.component';
import { MonitoringComponent } from './components/monitoring/monitoring.component';
import { AccueilComponent } from './components/accueil/accueil.component';

export const routes: Routes = [
    { path: '', component: AccueilComponent },
    { path: 'import-electeurs', component: ImportElecteurComponent },
    { path: 'gestion-candidats', component: GestionCandidatsComponent },
    { path: 'monitoring', component: MonitoringComponent },
];
