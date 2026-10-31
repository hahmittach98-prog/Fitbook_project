import { Routes } from '@angular/router';
import { Salles } from './pages/salles/salles';

export const routes: Routes = [
  { path: '', redirectTo: 'salles', pathMatch: 'full' },
  { path: 'salles', component: Salles }
];