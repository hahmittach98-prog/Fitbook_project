import { Component, inject, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Api } from '../../services/api';

@Component({
  selector: 'app-seances',
  imports: [FormsModule],
  templateUrl: './seances.html',
  styleUrl: './seances.css'
})
export class Seances {
  protected api = inject(Api);
  seances = signal<any[]>([]);
  erreur = signal('');
  message = signal('');

  date = '';
  prixMax = '';

  constructor() {
    this.chercher();
  }

  chercher() {
    this.api.getSeances({ date: this.date, prixMax: this.prixMax }).subscribe({
      next: (data) => this.seances.set(data),
      error: () => this.erreur.set('Impossible de charger les séances.')
    });
  }

  reinitialiser() {
    this.date = '';
    this.prixMax = '';
    this.chercher();
  }

  reserver(id: number) {
    this.message.set('');
    this.erreur.set('');
    this.api.reserver(id).subscribe({
      next: () => this.message.set('Réservation confirmée !'),
      error: (e) => {
        if (e.status === 403) {
          this.erreur.set('Seul un sportif peut réserver une séance.');
        } else {
          this.erreur.set(e.error?.erreur ?? 'Réservation impossible pour le moment.');
        }
      }
    });
  }
}