import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { Api } from '../services/api';

export const roleGuard = (...roles: string[]): CanActivateFn => () => {
  const api = inject(Api);
  const router = inject(Router);
  return api.aRole(...roles) || router.createUrlTree(['/connexion']);
};