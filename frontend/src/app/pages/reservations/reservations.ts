import { Component, inject, signal } from '@angular/core';
import { Api } from '../../services/api';

@Component({
  selector: 'app-reservations',
  imports: [],
  templateUrl: './reservations.html',
  styleUrl: './reservations.css'
})
export class Reservations {
  private api = inject(Api);
  reservations = signal<any[]>([]);
  erreur = signal('');
  message = signal('');

  constructor() {
    this.charger();
  }

  charger() {
    this.api.getReservations().subscribe({
      next: (data) => this.reservations.set(data),
      error: () => this.erreur.set('Impossible de charger vos réservations. Connectez-vous.')
    });
  }

  annuler(id: number) {
    this.message.set('');
    this.erreur.set('');
    this.api.annulerReservation(id).subscribe({
      next: () => {
        this.message.set('Réservation annulée.');
        this.charger();
      },
      error: (e) => this.erreur.set(e.error?.erreur ?? 'Annulation impossible pour le moment.')
    });
  }
}