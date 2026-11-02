import { Component, inject, signal } from '@angular/core';
import { Api } from '../../services/api';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-admin',
  imports: [FormsModule],
  templateUrl: './admin.html',
  styleUrl: './admin.css'
})
export class Admin {
  private api = inject(Api);

  users = signal<any[]>([]);
  erreur = signal('');
  message = signal('');
  avis = signal<any[]>([]);
  cours = signal<any[]>([]);
  formCours = { nom: '', description: '', niveau: '' };

  constructor() {
    this.charger();
    this.chargerAvis();
    this.chargerCours();
  }

  charger() {
    this.api.adminUsers().subscribe({
      next: (data) => this.users.set(data),
      error: () => this.erreur.set('Impossible de charger les utilisateurs.')
    });
  }

  verifier(id: number) {
    this.api.adminVerifier(id).subscribe({
      next: () => {
        this.message.set('Compte validé.');
        this.charger();
      },
      error: () => this.erreur.set('Validation impossible.')
    });
  }

  supprimer(id: number) {
    if (!confirm('Supprimer cet utilisateur ?')) return;
    this.api.adminSupprimer(id).subscribe({
      next: () => {
        this.message.set('Utilisateur supprimé.');
        this.charger();
        
      },
      error: () => this.erreur.set('Suppression impossible (cet utilisateur a des données liées).')
    });
  }
    chargerAvis() {
    this.api.adminAvis().subscribe({
      next: (data) => this.avis.set(data),
      error: () => this.erreur.set('Impossible de charger les avis.')
    });
  }

  moderer(a: any) {
    const statut = a.statut === 'masque' ? 'valide' : 'masque';
    this.api.adminModerer(a.id, statut).subscribe({
      next: () => {
        this.message.set(statut === 'masque' ? 'Avis masqué.' : 'Avis de nouveau visible.');
        this.chargerAvis();
      },
      error: () => this.erreur.set('Modération impossible.')
    });
  }
    chargerCours() {
    this.api.getCours().subscribe((data) => this.cours.set(data));
  }

  creerCours() {
    this.erreur.set('');
    this.message.set('');
    const f = this.formCours;
    this.api.creerCours({
      nom: f.nom,
      description: f.description || null,
      niveau: f.niveau
    }).subscribe({
      next: () => {
        this.message.set('Cours créé.');
        this.formCours = { nom: '', description: '', niveau: '' };
        this.chargerCours();
      },
      error: (e) => this.erreur.set(e.error?.erreur ?? 'Création du cours impossible.')
    });
  }

  supprimerCours(id: number) {
    if (!confirm('Supprimer ce cours ?')) return;
    this.api.supprimerCours(id).subscribe({
      next: () => {
        this.message.set('Cours supprimé.');
        this.chargerCours();
      },
      error: () => this.erreur.set('Suppression impossible (le cours est utilisé par des séances).')
    });
  }
}