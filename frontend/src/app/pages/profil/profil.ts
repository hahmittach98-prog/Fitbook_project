import { Component, inject, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Api } from '../../services/api';
import { Photo } from '../../components/photo/photo';
import { Telechargement } from '../../components/telechargement/telechargement';

@Component({
  selector: 'app-profil',
  imports: [FormsModule, Photo, Telechargement],
  templateUrl: './profil.html',
  styleUrl: './profil.css'
})
export class Profil {
  private api = inject(Api);
  profil = signal<any>(null);
  erreur = signal('');
  message = signal('');
  edition = signal(false);
  form = { nom: '', prenom: '', telephone: '' };

  constructor() {
    this.charger();
  }

  charger() {
    this.api.getProfil().subscribe({
      next: (data) => this.profil.set(data),
      error: () => this.erreur.set('Impossible de charger le profil. Connectez-vous.')
    });
  }

  modifier() {
    const p = this.profil();
    this.form = { nom: p.nom, prenom: p.prenom, telephone: p.telephone ?? '' };
    this.message.set('');
    this.erreur.set('');
    this.edition.set(true);
  }

  annuler() {
    this.edition.set(false);
  }

  enregistrer() {
    this.erreur.set('');
    this.api.modifierProfil(this.form).subscribe({
      next: () => {
        this.message.set('Profil mis à jour.');
        this.edition.set(false);
        this.charger();
      },
      error: (e) => this.erreur.set(e.error?.erreur ?? 'Modification impossible.')
    });
  }

  photoEnvoyee(nom: string) {
    this.profil.set({ ...this.profil(), imageName: nom });
  }
}