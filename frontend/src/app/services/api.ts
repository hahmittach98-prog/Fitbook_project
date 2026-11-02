import { Injectable, inject, signal, computed } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable, tap } from 'rxjs';

const API = 'https://127.0.0.1:8000/api';

@Injectable({ providedIn: 'root' })
export class Api {

  private http = inject(HttpClient);

  connecte = signal(!!localStorage.getItem('token'));
    roles = computed<string[]>(() => {
    if (!this.connecte()) return [];
    try {
      const charge = localStorage.getItem('token')!.split('.')[1].replace(/-/g, '+').replace(/_/g, '/');
      return JSON.parse(atob(charge)).roles ?? [];
    } catch {
      return [];
    }
  });

  aRole(...roles: string[]): boolean {
    return this.roles().some((r) => roles.includes(r));
  }


    private params(filtres: Record<string, any> = {}): HttpParams {
    let p = new HttpParams();
    for (const [cle, valeur] of Object.entries(filtres)) {
      if (valeur !== '' && valeur !== null && valeur !== undefined) {
        p = p.set(cle, String(valeur));
      }
    }
    return p;
  }

  getSalles(filtres: Record<string, any> = {}): Observable<any[]> {
    return this.http.get<any[]>(`${API}/salles`, { params: this.params(filtres) });
  }

  getCoachs(filtres: Record<string, any> = {}): Observable<any[]> {
    return this.http.get<any[]>(`${API}/coachs`, { params: this.params(filtres) });
  }

  getCours(filtres: Record<string, any> = {}): Observable<any[]> {
    return this.http.get<any[]>(`${API}/cours`, { params: this.params(filtres) });
  }

  getSeances(filtres: Record<string, any> = {}): Observable<any[]> {
    return this.http.get<any[]>(`${API}/seances`, { params: this.params(filtres) });
  }
  getProfil(): Observable<any> {
    return this.http.get<any>(`${API}/profil`);
  }

  reserver(seanceId: number): Observable<any> {
    return this.http.post<any>(`${API}/reservations`, { seanceId });
  }

  getReservations(): Observable<any[]> {
    return this.http.get<any[]>(`${API}/reservations`);
  }

  annulerReservation(id: number): Observable<any> {
    return this.http.delete<any>(`${API}/reservations/${id}`);
  }
    getSalle(id: number): Observable<any> {
    return this.http.get<any>(`${API}/salles/${id}`);
  }

  getCoursSalle(id: number): Observable<any[]> {
    return this.http.get<any[]>(`${API}/salles/${id}/cours`);
  }

  getCoach(id: number): Observable<any> {
    return this.http.get<any>(`${API}/coachs/${id}`);
  }

  getAvis(salleId?: number, coachId?: number): Observable<any[]> {
    let params = new HttpParams();
    if (salleId) { params = params.set('salleId', salleId); }
    if (coachId) { params = params.set('coachId', coachId); }
    return this.http.get<any[]>(`${API}/avis`, { params });
  }

  creerAvis(donnees: any): Observable<any> {
    return this.http.post<any>(`${API}/avis`, donnees);
  }

    getFavoris(): Observable<any> {
    return this.http.get<any>(`${API}/favoris`);
  }

  ajouterFavori(type: string, id: number): Observable<any> {
    return this.http.post<any>(`${API}/favoris/${type}/${id}`, {});
  }

  retirerFavori(type: string, id: number): Observable<any> {
    return this.http.delete<any>(`${API}/favoris/${type}/${id}`);
  }
    adminUsers(): Observable<any[]> {
    return this.http.get<any[]>(`${API}/admin/users`);
  }

  adminVerifier(id: number): Observable<any> {
    return this.http.put(`${API}/admin/users/${id}/verify`, {});
  }

  adminSupprimer(id: number): Observable<any> {
    return this.http.delete(`${API}/admin/users/${id}`);
  }
    creerSeance(data: any): Observable<any> {
    return this.http.post(`${API}/seances`, data);
  }

  supprimerSeance(id: number): Observable<any> {
    return this.http.delete(`${API}/seances/${id}`);
  }

  getReservationsSeance(id: number): Observable<any[]> {
    return this.http.get<any[]>(`${API}/reservations/seance/${id}`);
  }

    creerCoach(data: any): Observable<any> {
    return this.http.post(`${API}/coachs`, data);
  }
    adminAvis(): Observable<any[]> {
    return this.http.get<any[]>(`${API}/admin/avis`);
  }

  adminModerer(id: number, statut: string): Observable<any> {
    return this.http.put(`${API}/admin/avis/${id}/moderation`, { statut });
  }
    creerSalle(data: any): Observable<any> {
    return this.http.post(`${API}/salles`, data);
  }

  modifierSalle(id: number, data: any): Observable<any> {
    return this.http.put(`${API}/salles/${id}`, data);
  }

    creerCours(data: any): Observable<any> {
    return this.http.post(`${API}/cours`, data);
  }

  supprimerCours(id: number): Observable<any> {
    return this.http.delete(`${API}/cours/${id}`);
  }

  login(email: string, password: string): Observable<{ token: string }> {
    return this.http.post<{ token: string }>(`${API}/login`, { email, password }).pipe(
      tap((reponse) => {
        localStorage.setItem('token', reponse.token);
        this.connecte.set(true);
      })
    );
  }

  register(donnees: any): Observable<{ message: string; id: number }> {
    return this.http.post<{ message: string; id: number }>(`${API}/register`, donnees);
  }

  estConnecte(): boolean {
    return this.connecte();
  }

  deconnexion(): void {
    localStorage.removeItem('token');
    this.connecte.set(false);
  }
}