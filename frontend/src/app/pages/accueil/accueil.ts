import { Component, inject, signal } from '@angular/core';
import { RouterLink } from '@angular/router';
import { Api } from '../../services/api';
import { Photo } from '../../components/photo/photo';

@Component({
  selector: 'app-accueil',
  imports: [RouterLink, Photo],
  templateUrl: './accueil.html',
  styleUrl: './accueil.css'
})
export class Accueil {
  private api = inject(Api);
  cours = signal<any[]>([]);
  coachs = signal<any[]>([]);
  salles = signal<any[]>([]);

  constructor() {
    this.api.getCours().subscribe((d) => this.cours.set(d.slice(0, 4)));
    this.api.getCoachs().subscribe((d) => this.coachs.set(d.slice(0, 4)));
    this.api.getSalles().subscribe((d) => this.salles.set(d.slice(0, 3)));
  }
}