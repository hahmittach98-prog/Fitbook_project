import { Component, inject, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Api } from '../../services/api';

@Component({
  selector: 'app-espace-coach',
  imports: [FormsModule],
  templateUrl: './espace-coach.html',
  styleUrl: './espace-coach.css'
})
export class EspaceCoach {
  private api = inject(Api);

  coach = signal<any>(null);
  seances = signal<any[]>([]);
  cours = signal<any[]>([]);
  salles = signal<any[]>([]);
  reservations = signal<any[]>([]);
  voir = signal(0);
  erreur = signal('');
  message = signal('');
  profilManquant = signal(false);
  profil = { specialite: '', description: '', tarifHoraire: '' };

  form = { coursId: '', salleId: '', date: '', heureDebut: '', heureFin: '', capaciteMax: 10, prix: 10 };

  constructor() {
    this.api.getCours().subscribe((d) => this.cours.set(d));
    this.api.getSalles().subscribe((d) => this.salles.set(d));
    this.api.getProfil().subscribe((p: any) => {
      this.api.getCoachs().subscribe((liste: any[]) => {
        const moi = liste.find((c) => c.userId === p.id);
        if (moi) {
          this.coach.set(moi);
          this.chargerSeances();
                } else {
          this.profilManquant.set(true);
        }
      });
    });
  }

  chargerSeances() {
    this.api.getSeances({ coachId: this.coach().id }).subscribe((d) => this.seances.set(d));
  }
    creerProfil() {
    this.erreur.set('');
    const p = this.profil;
    this.api.creerCoach({
      specialite: p.specialite,
      description: p.description || null,
      tarifHoraire: p.tarifHoraire ? Number(p.tarifHoraire) : null
    }).subscribe({
      next: (c) => {
        this.coach.set(c);
        this.profilManquant.set(false);
        this.message.set('Profil coach créé.');
        this.chargerSeances();
      },
      error: (e) => this.erreur.set(e.error?.erreur ?? 'Création du profil impossible.')
    });
  }

  creer() {
    this.erreur.set('');
    this.message.set('');
    const f = this.form;
    this.api.creerSeance({
      coachId: this.coach().id,
      coursId: Number(f.coursId),
      salleId: Number(f.salleId),
      date: f.date,
      heureDebut: f.heureDebut,
      heureFin: f.heureFin,
      capaciteMax: Number(f.capaciteMax),
      prix: Number(f.prix)
    }).subscribe({
      next: () => {
        this.message.set('Séance créée.');
        this.chargerSeances();
      },
      error: (e) => this.erreur.set(e.error?.erreur ?? 'Données invalides, vérifiez le formulaire.')
    });
  }

  supprimer(id: number) {
    if (!confirm('Supprimer cette séance ?')) return;
    this.api.supprimerSeance(id).subscribe({
      next: () => {
        this.message.set('Séance supprimée.');
        this.chargerSeances();
      },
      error: () => this.erreur.set('Suppression impossible (des réservations existent peut-être).')
    });
  }

  afficherReservations(id: number) {
    if (this.voir() === id) {
      this.voir.set(0);
      return;
    }
    this.api.getReservationsSeance(id).subscribe((d) => {
      this.reservations.set(d);
      this.voir.set(id);
    });
  }
}