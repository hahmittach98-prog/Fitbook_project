import { Component, inject, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { Api } from '../../services/api';
import { Photo } from '../../components/photo/photo';

@Component({
  selector: 'app-accueil',
  imports: [RouterLink, FormsModule, Photo],
  templateUrl: './accueil.html',
  styleUrl: './accueil.css'
})
export class Accueil {
  protected api = inject(Api);
  cours = signal<any[]>([]);
  coachs = signal<any[]>([]);
  salles = signal<any[]>([]);
  seances = signal<any[]>([]);

  // les 3 filtres de la barre de recherche
  recherche = signal('');
  date = signal('');
  ville = signal('');

  // null tant qu'on n'a pas cliqué sur "Rechercher"
  resultats = signal<any[] | null>(null);

  constructor() {
    this.api.getSeances().subscribe((d) => this.seances.set(d));
    this.api.getCours().subscribe((d) => this.cours.set(d.slice(0, 4)));
    this.api.getCoachs().subscribe((d) => this.coachs.set(d.slice(0, 4)));
    this.api.getSalles().subscribe((d) => this.salles.set(d.slice(0, 3)));
  }

  // filtre les séances sur le texte (cours, salle, coach), la date et la ville
  chercher(event: Event) {
    event.preventDefault();
    const mot = this.recherche().toLowerCase().trim();
    const ville = this.ville().toLowerCase().trim();
    const jour = this.date();

    const trouvees = this.seances()
      .filter((s) => [s.coursNom, s.salleNom, s.coachNom].join(' ').toLowerCase().includes(mot))
      .filter((s) => !jour || s.date === jour)
      .filter((s) => (s.salleVille ?? '').toLowerCase().includes(ville));

    this.resultats.set(trouvees);
    setTimeout(() => document.getElementById('resultats')?.scrollIntoView({ behavior: 'smooth' }));
  }
}