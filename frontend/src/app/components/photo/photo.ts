import { Component, inject, input } from '@angular/core';
import { Api } from '../../services/api';

@Component({
  selector: 'app-photo',
  imports: [],
  templateUrl: './photo.html',
  styleUrl: './photo.css'
})
export class Photo {
  protected api = inject(Api);
  dossier = input.required<string>();
  nom = input<string | null | undefined>('');
}