import { Injectable, inject, signal } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, tap } from 'rxjs';

const API = 'https://127.0.0.1:8000/api';

@Injectable({ providedIn: 'root' })
export class Api {
  private http = inject(HttpClient);

  connecte = signal(!!localStorage.getItem('token'));

  getSalles(): Observable<any[]> {
    return this.http.get<any[]>(`${API}/salles`);
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