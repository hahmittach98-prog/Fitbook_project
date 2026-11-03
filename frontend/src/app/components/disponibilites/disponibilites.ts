import { Component, effect, inject, input, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Api } from '../../services/api';

@Component({
  selector: 'app-disponibilites',
  imports: [FormsModule],
  templateUrl: './disponibilites.html',
  styleUrl: './disponibilites.css',
})
export class Disponibilites {
  api = inject(Api);

  // Le parent donne soit un coachId, soit un salleId
  coachId = input<number | null>(null);
  salleId = input<number | null>(null);
  lecture = input(false);

  jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
  liste = signal<any[]>([]);
  erreur = signal('');

  // Champs du formulaire (ngModel)
  jour = 'Lundi';
  heureDebut = '09:00';
  heureFin = '17:00';

  constructor() {
    // Se relance tout seul quand le coachId ou le salleId change
    effect(() => {
      this.charger();
    });
  }

  charger(): void {
    const params: Record<string, number> = {};
    const coach = this.coachId();
    const salle = this.salleId();

    if (coach) {
      params['coachId'] = coach;
    } else if (salle) {
      params['salleId'] = salle;
    } else {
      this.liste.set([]);
      return;
    }
    this.api.listerDispos(params).subscribe((dispos) => this.liste.set(dispos));
  }

  ajouter(): void {
    this.erreur.set('');
    const donnees: any = {
      jourSemaine: this.jour,
      heureDebut: this.heureDebut,
      heureFin: this.heureFin,
    };
    if (this.coachId()) {
      donnees.coachId = this.coachId();
    } else {
      donnees.salleId = this.salleId();
    }

    this.api.creerDispo(donnees).subscribe({
      next: () => this.charger(),
      error: (e) => this.erreur.set(e.error?.erreur ?? 'Erreur lors de l’ajout'),
    });
  }

  supprimer(id: number): void {
    this.api.supprimerDispo(id).subscribe(() => this.charger());
  }
}