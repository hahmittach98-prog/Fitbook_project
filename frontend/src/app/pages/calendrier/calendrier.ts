import { Component, computed, inject, signal } from '@angular/core';
import { Api } from '../../services/api';

@Component({
  selector: 'app-calendrier',
  imports: [],
  templateUrl: './calendrier.html',
  styleUrl: './calendrier.css'
})
export class Calendrier {
  protected api = inject(Api);

  jours = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];

  seances = signal<any[]>([]);
  mois = signal(new Date());
  choisi = signal('');
  erreur = signal('');
  message = signal('');

  titre = computed(() =>
    this.mois().toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' })
  );

  grille = computed(() => {
    const m = this.mois();
    const annee = m.getFullYear();
    const index = m.getMonth();
    const decalage = (new Date(annee, index, 1).getDay() + 6) % 7; // le lundi en premier
    const nbJours = new Date(annee, index + 1, 0).getDate();

    const cases: any[] = [];
    for (let i = 0; i < decalage; i++) {
      cases.push(null);
    }
    for (let j = 1; j <= nbJours; j++) {
      const cle = `${annee}-${String(index + 1).padStart(2, '0')}-${String(j).padStart(2, '0')}`;
      cases.push({ jour: j, cle, seances: this.seances().filter((s) => s.date === cle) });
    }
    while (cases.length % 7 !== 0) {
      cases.push(null);
    }
    return cases;
  });

  seancesDuJour = computed(() => this.seances().filter((s) => s.date === this.choisi()));

  constructor() {
    this.api.getSeances().subscribe({
      next: (data) => {
        this.seances.set(data);
        // on se place sur la prochaine séance (ou la première)
        const aujourdhui = new Date().toISOString().slice(0, 10);
        const triees = [...data].sort((a, b) => a.date.localeCompare(b.date));
        const prochaine = triees.find((s) => s.date >= aujourdhui) ?? triees[0];
        if (prochaine) {
          const [a, m] = prochaine.date.split('-').map(Number);
          this.mois.set(new Date(a, m - 1, 1));
          this.choisi.set(prochaine.date);
        }
      },
      error: () => this.erreur.set('Impossible de charger les séances.')
    });
  }

  precedent() {
    const m = this.mois();
    this.mois.set(new Date(m.getFullYear(), m.getMonth() - 1, 1));
  }

  suivant() {
    const m = this.mois();
    this.mois.set(new Date(m.getFullYear(), m.getMonth() + 1, 1));
  }

  choisir(cle: string) {
    this.choisi.set(cle);
    this.message.set('');
    this.erreur.set('');
  }

  reserver(id: number) {
    this.message.set('');
    this.erreur.set('');
    this.api.reserver(id).subscribe({
      next: () => this.message.set('Réservation confirmée !'),
      error: (e) => {
        if (e.status === 403) {
          this.erreur.set('Seul un sportif peut réserver une séance.');
        } else {
          this.erreur.set(e.error?.erreur ?? 'Réservation impossible pour le moment.');
        }
      }
    });
  }
}