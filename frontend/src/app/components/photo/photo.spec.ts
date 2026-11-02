import { ComponentFixture, TestBed } from '@angular/core/testing';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { Api } from '../../services/api';
import { Photo } from './photo';

describe('Photo', () => {
  let fixture: ComponentFixture<Photo>;

  // Faux Api : seule la méthode utilisée par le composant est simulée
  const mockApi = { urlImage: vi.fn() };

  beforeEach(async () => {
    vi.clearAllMocks();

    await TestBed.configureTestingModule({
      imports: [Photo],
      providers: [{ provide: Api, useValue: mockApi }]
    }).compileComponents();

    fixture = TestBed.createComponent(Photo);
    fixture.componentRef.setInput('dossier', 'salles');
  });

  it('displays the image when a file name is given', () => {
    // Arrange
    mockApi.urlImage.mockReturnValue('https://test/images/salles/basicfit.jpg');
    fixture.componentRef.setInput('nom', 'basicfit.jpg');

    // Act
    fixture.detectChanges();

    // Assert
    const $image = fixture.nativeElement.querySelector('img.photo') as HTMLImageElement;
    expect($image.src).toContain('basicfit.jpg');
  });

  it('displays an empty frame when there is no photo', () => {
    fixture.componentRef.setInput('nom', '');

    fixture.detectChanges();

    expect(fixture.nativeElement.querySelector('img')).toBeNull();
    expect(fixture.nativeElement.querySelector('.photo.vide')).not.toBeNull();
  });
});