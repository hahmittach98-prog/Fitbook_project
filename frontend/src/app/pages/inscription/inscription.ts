import { Component, inject, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Api } from '../../services/api';

@Component({
  selector: 'app-inscription',
  imports: [FormsModule],
  templateUrl: './inscription.html',
  styleUrl: './inscription.css'
})
export class Inscription {
  private api = inject(Api);

  nom = '';
  prenom = '';
  email = '';
  password = '';
  telephone = '';
  role = 'sportif';

  erreur = signal('');
  message = signal('');

  envoyer() {
    this.erreur.set('');
    this.message.set('');

    const donnees: any = {
      nom: this.nom,
      prenom: this.prenom,
      email: this.email,
      password: this.password,
      role: this.role
    };
    if (this.telephone) {
      donnees.telephone = this.telephone;
    }

    this.api.register(donnees).subscribe({
      next: (reponse) => this.message.set(reponse.message),
      error: (e) => {
        if (e.status === 409) {
          this.erreur.set('Cet email est déjà utilisé.');
        } else if (e.status === 422) {
          const liste = e.error?.erreurs;
          this.erreur.set(Array.isArray(liste) ? liste.join(' ') : 'Données invalides : vérifiez les champs.');
        } else {
          this.erreur.set('Inscription impossible pour le moment.');
        }
      }
    });
  }
}