import { Routes } from '@angular/router';
import { Salles } from './pages/salles/salles';
import { Connexion } from './pages/connexion/connexion';
import { Inscription } from './pages/inscription/inscription';

export const routes: Routes = [
  { path: '', redirectTo: 'salles', pathMatch: 'full' },
  { path: 'salles', component: Salles },
  { path: 'connexion', component: Connexion },
  { path: 'inscription', component: Inscription }
];