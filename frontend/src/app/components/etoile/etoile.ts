import { Component, computed, inject, input } from '@angular/core';
import { Api } from '../../services/api';
import { Favoris, TypeFavori } from '../../services/favoris';

@Component({
  selector: 'app-etoile',
  imports: [],
  templateUrl: './etoile.html',
  styleUrl: './etoile.css'
})
export class Etoile {
  protected api = inject(Api);
  private favoris = inject(Favoris);

  type = input.required<TypeFavori>();
  id = input.required<number>();

  actif = computed(() => this.favoris.estFavori(this.type(), this.id()));

  clic() {
    this.favoris.basculer(this.type(), this.id());
  }
}