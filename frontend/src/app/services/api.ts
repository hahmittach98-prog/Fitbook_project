import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

const API = 'https://127.0.0.1:8000/api';

@Injectable({ providedIn: 'root' })
export class Api {
  private http = inject(HttpClient);

  getSalles(): Observable<any[]> {
    return this.http.get<any[]>(`${API}/salles`);
  }
}