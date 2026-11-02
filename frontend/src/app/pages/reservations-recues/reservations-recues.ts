import { Component, inject, signal } from '@angular/core';
import { Api } from '../../services/api';

@Component({
  selector: 'app-reservations-recues',
  imports: [],
  templateUrl: './reservations-recues.html',
  styleUrl: './reservations-recues.css',
})
export class ReservationsRecues {
  private api = inject(Api);

  seances = signal<any[]>([]);
  reservations = signal<any[]>([]);
  voir = signal(0);

  constructor() {
    this.api.getProfil().subscribe((p: any) => {
      if (this.api.aRole('ROLE_ADMIN')) {
        // l'admin voit toutes les séances
        this.api.getSeances().subscribe((d) => this.seances.set(d));
      } else if (this.api.aRole('ROLE_COACH')) {
        // le coach voit ses séances
        this.api.getCoachs().subscribe((coachs: any[]) => {
          const moi = coachs.find((c) => c.userId === p.id);
          if (moi) {
            this.api.getSeances({ coachId: moi.id }).subscribe((d) => this.seances.set(d));
          }
        });
      } else if (this.api.aRole('ROLE_SALLE')) {
        // la salle voit les séances de ses salles
        this.api.getSalles().subscribe((salles: any[]) => {
          const ids = salles.filter((s) => s.gestionnaireId === p.id).map((s) => s.id);
          this.api.getSeances().subscribe((tous: any[]) => {
            this.seances.set(tous.filter((s) => ids.includes(s.salleId)));
          });
        });
      }
    });
  }

  afficher(id: number) {
    if (this.voir() === id) {
      this.voir.set(0);
      return;
    }
    this.api.getReservationsSeance(id).subscribe((d) => {
      this.reservations.set(d);
      this.voir.set(id);
    });
  }
}