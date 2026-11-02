import { Component, inject, signal } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Api } from '../../services/api';
import { Avis } from '../../components/avis/avis';

@Component({
  selector: 'app-salle-detail',
  imports: [Avis],
  templateUrl: './salle-detail.html',
  styleUrl: './salle-detail.css'
})
export class SalleDetail {
  private api = inject(Api);
  private route = inject(ActivatedRoute);

  id = Number(this.route.snapshot.paramMap.get('id'));
  salle = signal<any>(null);
  cours = signal<any[]>([]);
  erreur = signal('');

  constructor() {
    this.api.getSalle(this.id).subscribe({
      next: (data) => this.salle.set(data),
      error: () => this.erreur.set('Salle introuvable.')
    });
    this.api.getCoursSalle(this.id).subscribe({
      next: (data) => this.cours.set(data),
      error: () => {}
    });
  }
}