import { Component, inject, signal } from '@angular/core';
import { Api } from '../../services/api';
import { RouterLink } from '@angular/router';
import { Etoile } from '../../components/etoile/etoile';

@Component({
  selector: 'app-salles',
  imports: [RouterLink, Etoile],
  templateUrl: './salles.html',
  styleUrl: './salles.css'
})
export class Salles {
  private api = inject(Api);
  salles = signal<any[]>([]);
  erreur = signal('');

  constructor() {
    this.api.getSalles().subscribe({
      next: (data) => this.salles.set(data),
      error: () => this.erreur.set('Impossible de charger les salles.')
    });
  }
}