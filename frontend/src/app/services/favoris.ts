import { Injectable, effect, inject, signal } from '@angular/core';
import { Api } from './api';

export type TypeFavori = 'salles' | 'coachs' | 'cours';

@Injectable({ providedIn: 'root' })
export class Favoris {
  private api = inject(Api);

  liste = signal<{ salles: any[]; coachs: any[]; cours: any[] }>({ salles: [], coachs: [], cours: [] });

  constructor() {
    // se recharge tout seul à la connexion et se vide à la déconnexion
    effect(() => {
      if (this.api.connecte()) {
        this.charger();
      } else {
        this.liste.set({ salles: [], coachs: [], cours: [] });
      }
    });
  }

  charger() {
    this.api.getFavoris().subscribe({
      next: (data) => this.liste.set(data),
      error: () => {}
    });
  }

  estFavori(type: TypeFavori, id: number): boolean {
    return this.liste()[type].some((x: any) => x.id === id);
  }

  basculer(type: TypeFavori, id: number) {
    const action = this.estFavori(type, id)
      ? this.api.retirerFavori(type, id)
      : this.api.ajouterFavori(type, id);
    action.subscribe({ next: () => this.charger() });
  }
}