import { Component, inject } from '@angular/core';
import { Router, RouterLink, RouterOutlet } from '@angular/router';
import { Api } from './services/api';

@Component({
  selector: 'app-root',
  imports: [RouterOutlet, RouterLink],
  templateUrl: './app.html',
  styleUrl: './app.css'
})
export class App {
  protected api = inject(Api);
  private router = inject(Router);

  deconnexion() {
    this.api.deconnexion();
    this.router.navigate(['/connexion']);
  }
}