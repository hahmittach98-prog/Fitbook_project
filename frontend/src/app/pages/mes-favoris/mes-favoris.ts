import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { Favoris } from '../../services/favoris';
import { Etoile } from '../../components/etoile/etoile';

@Component({
  selector: 'app-mes-favoris',
  imports: [RouterLink, Etoile],
  templateUrl: './mes-favoris.html',
  styleUrl: './mes-favoris.css'
})
export class MesFavoris {
  protected favoris = inject(Favoris);
}