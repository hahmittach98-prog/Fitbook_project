import { Component, inject, signal } from '@angular/core';
import { Api } from '../../services/api';
import { Etoile } from '../../components/etoile/etoile';

@Component({
  selector: 'app-cours',
  imports: [Etoile],
  templateUrl: './cours.html',
  styleUrl: './cours.css'
})
export class Cours {
  private api = inject(Api);
  cours = signal<any[]>([]);
  erreur = signal('');

  constructor() {
    this.api.getCours().subscribe({
      next: (data) => this.cours.set(data),
      error: () => this.erreur.set('Impossible de charger les cours.')
    });
  }
}