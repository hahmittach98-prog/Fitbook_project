import { Routes } from '@angular/router';
import { Salles } from './pages/salles/salles';
import { Connexion } from './pages/connexion/connexion';
import { Inscription } from './pages/inscription/inscription';
import { Coachs } from './pages/coachs/coachs';
import { Cours } from './pages/cours/cours';
import { Profil } from './pages/profil/profil';
import { Seances } from './pages/seances/seances';
import { Reservations } from './pages/reservations/reservations';
import { SalleDetail } from './pages/salle-detail/salle-detail';
import { CoachDetail } from './pages/coach-detail/coach-detail';
import { MesFavoris } from './pages/mes-favoris/mes-favoris';
import { Calendrier } from './pages/calendrier/calendrier';
import { Admin } from './pages/admin/admin';
import { roleGuard } from './securite/role';
import { EspaceCoach } from './pages/espace-coach/espace-coach';
import { EspaceSalle } from './pages/espace-salle/espace-salle';


export const routes: Routes = [
  { path: '', redirectTo: 'salles', pathMatch: 'full' },
  { path: 'salles', component: Salles },
  { path: 'connexion', component: Connexion },
  { path: 'inscription', component: Inscription },
  { path: 'coachs', component: Coachs },
  { path: 'cours', component: Cours },
  { path: 'profil', component: Profil },
  { path: 'seances', component: Seances },
  { path: 'reservations', component: Reservations },
  { path: 'salles/:id', component: SalleDetail },
  { path: 'coachs/:id', component: CoachDetail },
  { path: 'favoris', component: MesFavoris },
  { path: 'calendrier', component: Calendrier },
  { path: 'admin', component: Admin, canActivate: [roleGuard('ROLE_ADMIN')] },
  { path: 'espace-coach', component: EspaceCoach, canActivate: [roleGuard('ROLE_COACH')] },
  { path: 'espace-salle', component: EspaceSalle, canActivate: [roleGuard('ROLE_SALLE')] }

];