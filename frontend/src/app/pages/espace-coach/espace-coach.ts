import { Component, inject, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Api } from '../../services/api';
import { Disponibilites } from '../../components/disponibilites/disponibilites';
import { Avis } from '../../components/avis/avis';


@Component({
  selector: 'app-espace-coach',
  imports: [FormsModule, Disponibilites, Avis],
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
  monProfil = { specialite: '', description: '', tarifHoraire: '' };

  form = { coursId: '', salleId: '', date: '', heureDebut: '', heureFin: '', capaciteMax: 10, prix: 10 };

  constructor() {
    this.api.getCours().subscribe((d) => this.cours.set(d));
    this.api.getSalles().subscribe((d) => this.salles.set(d));
    this.api.getProfil().subscribe((p: any) => {
      this.api.getCoachs().subscribe((liste: any[]) => {
        const moi = liste.find((c) => c.userId === p.id);
        if (moi) {
          this.coach.set(moi);
          this.remplirProfil(moi);
          this.chargerSeances();
                } else {
          this.profilManquant.set(true);
        }
      });
    });
  }
    remplirProfil(c: any) {
    this.monProfil = {
      specialite: c.specialite ?? '',
      description: c.description ?? '',
      tarifHoraire: c.tarifHoraire != null ? String(c.tarifHoraire) : ''
    };
  }

  enregistrerProfil() {
    this.erreur.set('');
    this.message.set('');
    const p = this.monProfil;
    this.api.modifierCoach(this.coach().id, {
      specialite: p.specialite,
      description: p.description || null,
      tarifHoraire: p.tarifHoraire ? Number(p.tarifHoraire) : null
    }).subscribe({
      next: (c) => {
        this.coach.set({ ...this.coach(), ...c });
        this.message.set('Profil mis à jour.');
      },
      error: (e) => this.erreur.set(e.error?.erreur ?? 'Modification impossible.')
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
        this.remplirProfil(c);
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

    photoEnvoyee(nom: string) {
    this.coach.set({ ...this.coach(), imageName: nom });
  }
}