import { Component, inject, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { Api } from '../../services/api';
import { Etoile } from '../../components/etoile/etoile';

@Component({
  selector: 'app-coachs',
  imports: [FormsModule, RouterLink, Etoile],
  templateUrl: './coachs.html',
  styleUrl: './coachs.css'
})
export class Coachs {
  private api = inject(Api);
  coachs = signal<any[]>([]);
  erreur = signal('');

  q = '';
  specialite = '';
  tarifMax = '';

  constructor() {
    this.chercher();
  }

  chercher() {
    this.erreur.set('');
    this.api.getCoachs({ q: this.q, specialite: this.specialite, tarifMax: this.tarifMax }).subscribe({
      next: (data) => this.coachs.set(data),
      error: () => this.erreur.set('Impossible de charger les coachs.')
    });
  }

  reinitialiser() {
    this.q = '';
    this.specialite = '';
    this.tarifMax = '';
    this.chercher();
  }
}