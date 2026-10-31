import { Routes } from '@angular/router';
import { Salles } from './pages/salles/salles';
import { Connexion } from './pages/connexion/connexion';
import { Inscription } from './pages/inscription/inscription';
import { Coachs } from './pages/coachs/coachs';
import { Cours } from './pages/cours/cours';
import { Profil } from './pages/profil/profil';

export const routes: Routes = [
  { path: '', redirectTo: 'salles', pathMatch: 'full' },
  { path: 'salles', component: Salles },
  { path: 'connexion', component: Connexion },
  { path: 'inscription', component: Inscription },
  { path: 'coachs', component: Coachs },
  { path: 'cours', component: Cours },
  { path: 'profil', component: Profil }
];