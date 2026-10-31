import { HttpInterceptorFn } from '@angular/common/http';
import { catchError, throwError } from 'rxjs';

export const tokenInterceptor: HttpInterceptorFn = (req, next) => {
  const token = localStorage.getItem('token');
  const estAuth = req.url.endsWith('/login') || req.url.endsWith('/register');

  if (token && !estAuth) {
    req = req.clone({ setHeaders: { Authorization: `Bearer ${token}` } });
  }

  return next(req).pipe(
    catchError((erreur) => {
      if (erreur.status === 401 && token && !estAuth) {
        // token expiré : on le supprime et on retourne à la connexion
        localStorage.removeItem('token');
        window.location.href = '/connexion';
      }
      return throwError(() => erreur);
    })
  );
};