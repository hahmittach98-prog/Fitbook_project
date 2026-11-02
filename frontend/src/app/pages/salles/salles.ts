import { Component, inject, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { Api } from '../../services/api';
import { Etoile } from '../../components/etoile/etoile';

@Component({
  selector: 'app-salles',
  imports: [FormsModule, RouterLink, Etoile],
  templateUrl: './salles.html',
  styleUrl: './salles.css'
})
export class Salles {
  private api = inject(Api);
  salles = signal<any[]>([]);
  erreur = signal('');

  q = '';
  ville = '';
  capaciteMin = '';

  constructor() {
    this.chercher();
  }

  chercher() {
    this.erreur.set('');
    this.api.getSalles({ q: this.q, ville: this.ville, capaciteMin: this.capaciteMin }).subscribe({
      next: (data) => this.salles.set(data),
      error: () => this.erreur.set('Impossible de charger les salles.')
    });
  }

  reinitialiser() {
    this.q = '';
    this.ville = '';
    this.capaciteMin = '';
    this.chercher();
  }
}