import { HttpClient } from '@angular/common/http';
import { TestBed } from '@angular/core/testing';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { Api } from './api';

describe('Api', () => {
  let service: Api;

  // Faux HttpClient : on ne veut pas appeler le vrai serveur dans un test unitaire
  const mockHttp = { get: vi.fn(), post: vi.fn(), put: vi.fn(), delete: vi.fn() };

  // Fabrique un faux token JWT qui contient les rôles donnés
  function fauxToken(roles: string[]): string {
    return 'entete.' + btoa(JSON.stringify({ roles })) + '.signature';
  }

  beforeEach(() => {
    vi.clearAllMocks();
    localStorage.clear();

    TestBed.configureTestingModule({
      providers: [Api, { provide: HttpClient, useValue: mockHttp }]
    });
    service = TestBed.inject(Api);
  });

  it('refuses every role when nobody is logged in', () => {
    expect(service.aRole('ROLE_ADMIN')).toBe(false);
  });

  it('accepts a role contained in the token', () => {
    // Arrange
    localStorage.setItem('token', fauxToken(['ROLE_COACH', 'ROLE_USER']));
    service.connecte.set(true);

    // Act
    const resultat = service.aRole('ROLE_COACH');

    // Assert
    expect(resultat).toBe(true);
  });

  it('refuses a role missing from the token', () => {
    localStorage.setItem('token', fauxToken(['ROLE_SPORTIF', 'ROLE_USER']));
    service.connecte.set(true);

    expect(service.aRole('ROLE_ADMIN')).toBe(false);
  });

  it('accepts the user when one of several roles matches', () => {
    localStorage.setItem('token', fauxToken(['ROLE_COACH']));
    service.connecte.set(true);

    expect(service.aRole('ROLE_ADMIN', 'ROLE_COACH')).toBe(true);
  });

  it('builds the image address from the folder and the file name', () => {
    expect(service.urlImage('salles', 'basicfit.jpg')).toBe('https://127.0.0.1:8000/images/salles/basicfit.jpg');
  });

  it('returns an empty address when there is no image', () => {
    expect(service.urlImage('salles', null)).toBe('');
    expect(service.urlImage('salles', '')).toBe('');
  });
});