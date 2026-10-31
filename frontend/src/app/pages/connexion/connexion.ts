import { Component, inject, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { Api } from '../../services/api';

@Component({
  selector: 'app-connexion',
  imports: [FormsModule],
  templateUrl: './connexion.html',
  styleUrl: './connexion.css'
})
export class Connexion {
  private api = inject(Api);
  private router = inject(Router);

  email = '';
  password = '';
  erreur = signal('');

  envoyer() {
    this.erreur.set('');
    this.api.login(this.email, this.password).subscribe({
      next: () => this.router.navigate(['/salles']),
      error: (e) => this.erreur.set(
        e.status === 401 ? 'Email ou mot de passe incorrect.' : 'Connexion impossible pour le moment.'
      )
    });
  }
}