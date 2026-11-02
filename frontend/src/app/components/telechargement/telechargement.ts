import { Component, inject, input, output, signal } from '@angular/core';
import { Api } from '../../services/api';

@Component({
  selector: 'app-telechargement',
  imports: [],
  templateUrl: './telechargement.html',
  styleUrl: './telechargement.css'
})
export class Telechargement {
  private api = inject(Api);

  type = input.required<'profil' | 'coachs' | 'salles'>();
  id = input<number>(0);
  envoye = output<string>();

  fichier: File | null = null;
  message = signal('');
  erreur = signal('');

  choisir(event: Event) {
    this.fichier = (event.target as HTMLInputElement).files?.[0] ?? null;
  }

  envoyer() {
    this.message.set('');
    this.erreur.set('');
    if (!this.fichier) {
      this.erreur.set('Choisissez une image.');
      return;
    }
    this.api.envoyerImage(this.type(), this.id(), this.fichier).subscribe({
      next: (r) => {
        this.message.set('Photo enregistrée.');
        this.envoye.emit(r.imageName);
      },
      error: (e) => this.erreur.set(e.error?.erreur ?? 'Envoi impossible (format ou taille refusés).')
    });
  }
}