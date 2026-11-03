import { Component, inject, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Api } from '../../services/api';
import { Etoile } from '../../components/etoile/etoile';

@Component({
  selector: 'app-cours',
  imports: [FormsModule, Etoile],
  templateUrl: './cours.html',
  styleUrl: './cours.css'
})
export class Cours {
  private api = inject(Api);
  cours = signal<any[]>([]);
  erreur = signal('');

  q = '';
  niveau = '';

  constructor() {
    this.chercher();
  }

  chercher() {
    this.erreur.set('');
    this.api.getCours({ q: this.q, niveau: this.niveau }).subscribe({
      next: (data) => this.cours.set(data),
      error: () => this.erreur.set('Impossible de charger les cours.')
    });
  }

  reinitialiser() {
    this.q = '';
    this.niveau = '';
    this.chercher();
  }
}