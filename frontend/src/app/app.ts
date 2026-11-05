import { Component, HostListener, inject } from '@angular/core';
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

  // le menu "Mon espace" se ferme quand on clique ailleurs ou sur un de ses liens
  @HostListener('document:click', ['$event'])
  fermerMenu(event: Event) {
    const menu = document.querySelector('details.menu-espace') as HTMLDetailsElement | null;
    if (!menu || !menu.open) {
      return;
    }
    const cible = event.target as HTMLElement;
    if (!menu.contains(cible) || cible.closest('.menu-liste a')) {
      menu.open = false;
    }
  }

  deconnexion() {
    this.api.deconnexion();
    this.router.navigate(['/connexion']);
  }
}