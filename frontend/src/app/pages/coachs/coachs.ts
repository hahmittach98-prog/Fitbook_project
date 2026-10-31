import { Component, inject, signal } from '@angular/core';
import { Api } from '../../services/api';

@Component({
  selector: 'app-coachs',
  imports: [],
  templateUrl: './coachs.html',
  styleUrl: './coachs.css'
})
export class Coachs {
  private api = inject(Api);
  coachs = signal<any[]>([]);
  erreur = signal('');

  constructor() {
    this.api.getCoachs().subscribe({
      next: (data) => this.coachs.set(data),
      error: () => this.erreur.set('Impossible de charger les coachs.')
    });
  }
}