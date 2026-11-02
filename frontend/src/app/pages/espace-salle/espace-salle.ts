import { Component, inject, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Api } from '../../services/api';

@Component({
  selector: 'app-espace-salle',
  imports: [FormsModule],
  templateUrl: './espace-salle.html',
  styleUrl: './espace-salle.css'
})
export class EspaceSalle {
  private api = inject(Api);

  salles = signal<any[]>([]);
  seances = signal<any[]>([]);
  reservations = signal<any[]>([]);
  voir = signal(0);
  edition = signal(0);
  erreur = signal('');
  message = signal('');
    cours = signal<any[]>([]);
  coachs = signal<any[]>([]);
  formSeance = { salleId: '', coachId: '', coursId: '', date: '', heureDebut: '', heureFin: '', capaciteMax: 10, prix: 10 };

  form = { nom: '', ville: '', adresse: '', capacite: '', telephone: '', description: '' };

  constructor() {
    this.charger();
    this.api.getCours().subscribe((d) => this.cours.set(d));
    this.api.getCoachs().subscribe((d) => this.coachs.set(d));
  }

  charger() {
    this.api.getProfil().subscribe((p: any) => {
      this.api.getSalles().subscribe((liste: any[]) => {
        const mes = liste.filter((s) => s.gestionnaireId === p.id);
        this.salles.set(mes);
        this.api.getSeances().subscribe((tous: any[]) => {
          const ids = mes.map((s) => s.id);
          this.seances.set(tous.filter((s) => ids.includes(s.salleId)));
        });
      });
    });
  }

  envoyer() {
    this.erreur.set('');
    this.message.set('');
    const f = this.form;
    const data = {
      nom: f.nom,
      ville: f.ville,
      adresse: f.adresse,
      capacite: Number(f.capacite),
      telephone: f.telephone || null,
      description: f.description || null
    };
    const requete = this.edition()
      ? this.api.modifierSalle(this.edition(), data)
      : this.api.creerSalle(data);

    requete.subscribe({
      next: () => {
        this.message.set(this.edition() ? 'Salle modifiée.' : 'Salle créée.');
        this.annuler();
        this.charger();
      },
      error: (e) => this.erreur.set(e.error?.erreur ?? 'Enregistrement impossible.')
    });
  }

  modifier(s: any) {
    this.edition.set(s.id);
    this.form = {
      nom: s.nom,
      ville: s.ville,
      adresse: s.adresse,
      capacite: String(s.capacite),
      telephone: s.telephone ?? '',
      description: s.description ?? ''
    };
  }

  annuler() {
    this.edition.set(0);
    this.form = { nom: '', ville: '', adresse: '', capacite: '', telephone: '', description: '' };
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
  
    creerSeance() {
    this.erreur.set('');
    this.message.set('');
    const f = this.formSeance;
    this.api.creerSeance({
      salleId: Number(f.salleId),
      coachId: Number(f.coachId),
      coursId: Number(f.coursId),
      date: f.date,
      heureDebut: f.heureDebut,
      heureFin: f.heureFin,
      capaciteMax: Number(f.capaciteMax),
      prix: Number(f.prix)
    }).subscribe({
      next: () => {
        this.message.set('Séance créée.');
        this.charger();
      },
      error: (e) => this.erreur.set(e.error?.erreur ?? 'Données invalides, vérifiez le formulaire.')
    });
  }
}