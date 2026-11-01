import { Component, inject, signal } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Api } from '../../services/api';
import { Avis } from '../../components/avis/avis';

@Component({
  selector: 'app-coach-detail',
  imports: [Avis],
  templateUrl: './coach-detail.html',
  styleUrl: './coach-detail.css'
})
export class CoachDetail {
  private api = inject(Api);
  private route = inject(ActivatedRoute);

  id = Number(this.route.snapshot.paramMap.get('id'));
  coach = signal<any>(null);
  erreur = signal('');

  constructor() {
    this.api.getCoach(this.id).subscribe({
      next: (data) => this.coach.set(data),
      error: () => this.erreur.set('Coach introuvable.')
    });
  }
}