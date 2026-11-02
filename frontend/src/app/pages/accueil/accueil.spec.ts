import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting } from '@angular/common/http/testing';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { provideRouter } from '@angular/router';
import { beforeEach, describe, expect, it } from 'vitest';
import { Accueil } from './accueil';

describe('Accueil', () => {
  let fixture: ComponentFixture<Accueil>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [Accueil],
      providers: [provideHttpClient(), provideHttpClientTesting(), provideRouter([])]
    }).compileComponents();

    fixture = TestBed.createComponent(Accueil);
  });

  it('should create', () => {
    expect(fixture.componentInstance).toBeTruthy();
  });
});