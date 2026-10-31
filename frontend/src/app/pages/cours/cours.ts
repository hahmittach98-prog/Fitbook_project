import { Component, inject, signal } from '@angular/core';
import { Api } from '../../services/api';

@Component({
  selector: 'app-cours',
  imports: [],
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