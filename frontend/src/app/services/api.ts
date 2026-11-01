import { Injectable, inject, signal } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable, tap } from 'rxjs';

const API = 'https://127.0.0.1:8000/api';

@Injectable({ providedIn: 'root' })
export class Api {
  private http = inject(HttpClient);

  connecte = signal(!!localStorage.getItem('token'));

  getSalles(): Observable<any[]> {
    return this.http.get<any[]>(`${API}/salles`);
  }
    getCoachs(): Observable<any[]> {
    return this.http.get<any[]>(`${API}/coachs`);
  }

  getCours(): Observable<any[]> {
    return this.http.get<any[]>(`${API}/cours`);
  }
    getProfil(): Observable<any> {
    return this.http.get<any>(`${API}/profil`);
  }

    getSeances(): Observable<any[]> {
    return this.http.get<any[]>(`${API}/seances`);
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