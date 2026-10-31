import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, tap } from 'rxjs';

const API = 'https://127.0.0.1:8000/api';

@Injectable({ providedIn: 'root' })
export class Api {
  private http = inject(HttpClient);

  getSalles(): Observable<any[]> {
    return this.http.get<any[]>(`${API}/salles`);
  }

  login(email: string, password: string): Observable<{ token: string }> {
    return this.http.post<{ token: string }>(`${API}/login`, { email, password }).pipe(
      tap((reponse) => localStorage.setItem('token', reponse.token))
    );
  }

  estConnecte(): boolean {
    return !!localStorage.getItem('token');
  }

  deconnexion(): void {
    localStorage.removeItem('token');
  }
}