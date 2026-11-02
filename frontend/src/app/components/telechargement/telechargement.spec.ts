import { ComponentFixture, TestBed } from '@angular/core/testing';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { Api } from '../../services/api';
import { Telechargement } from './telechargement';

describe('Telechargement', () => {
  let fixture: ComponentFixture<Telechargement>;

  // Faux Api : on vérifie surtout qu'elle n'est PAS appelée sans fichier
  const mockApi = { envoyerImage: vi.fn() };

  beforeEach(async () => {
    vi.clearAllMocks();

    await TestBed.configureTestingModule({
      imports: [Telechargement],
      providers: [{ provide: Api, useValue: mockApi }]
    }).compileComponents();

    fixture = TestBed.createComponent(Telechargement);
    fixture.componentRef.setInput('type', 'salles');
    fixture.detectChanges();
  });

  it('shows an error when the user saves without choosing a file', () => {
    // Act : l'utilisateur clique sur "Enregistrer la photo"
    const $bouton = fixture.nativeElement.querySelector('button') as HTMLButtonElement;
    $bouton.click();
    fixture.detectChanges();

    // Assert
    const $erreur = fixture.nativeElement.querySelector('.erreur');
    expect($erreur?.textContent).toContain('Choisissez une image');
  });

  it('does not call the API when no file is chosen', () => {
    const $bouton = fixture.nativeElement.querySelector('button') as HTMLButtonElement;
    $bouton.click();

    expect(mockApi.envoyerImage).not.toHaveBeenCalled();
  });
});