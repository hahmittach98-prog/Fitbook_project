import { Component, inject, signal } from '@angular/core';
import { Api } from '../../services/api';

@Component({
  selector: 'app-profil',
  imports: [],
  templateUrl: './profil.html',
  styleUrl: './profil.css'
})
export class Profil {
  private api = inject(Api);
  profil = signal<any>(null);
  erreur = signal('');

  constructor() {
    this.api.getProfil().subscribe({
      next: (data) => this.profil.set(data),
      error: () => this.erreur.set('Impossible de charger le profil. Connectez-vous.')
    });
  }
}