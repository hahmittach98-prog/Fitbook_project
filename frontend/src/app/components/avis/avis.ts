import { Component, OnInit, inject, input, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Api } from '../../services/api';

@Component({
  selector: 'app-avis',
  imports: [FormsModule],
  templateUrl: './avis.html',
  styleUrl: './avis.css'
})
export class Avis implements OnInit {
  protected api = inject(Api);

  salleId = input<number>();
  coachId = input<number>();

  liste = signal<any[]>([]);
  erreur = signal('');
  message = signal('');

  notes = [5, 4, 3, 2, 1];
  note = 5;
  commentaire = '';

  ngOnInit() {
    this.charger();
  }

  charger() {
    this.api.getAvis(this.salleId(), this.coachId()).subscribe({
      next: (data) => this.liste.set(data),
      error: () => this.erreur.set('Impossible de charger les avis.')
    });
  }

  envoyer() {
    this.erreur.set('');
    this.message.set('');

    const donnees: any = { note: Number(this.note), commentaire: this.commentaire };
    if (this.salleId()) {
      donnees.salleId = this.salleId();
    } else {
      donnees.coachId = this.coachId();
    }

    this.api.creerAvis(donnees).subscribe({
      next: () => {
        this.message.set('Merci pour votre avis !');
        this.commentaire = '';
        this.charger();
      },
      error: (e) => this.erreur.set(e.error?.erreur ?? 'Impossible d’envoyer l’avis.')
    });
  }
}