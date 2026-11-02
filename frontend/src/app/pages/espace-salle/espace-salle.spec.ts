import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting } from '@angular/common/http/testing';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { provideRouter } from '@angular/router';
import { beforeEach, describe, expect, it } from 'vitest';
import { EspaceSalle } from './espace-salle';

describe('EspaceSalle', () => {
  let fixture: ComponentFixture<EspaceSalle>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [EspaceSalle],
      providers: [provideHttpClient(), provideHttpClientTesting(), provideRouter([])]
    }).compileComponents();

    fixture = TestBed.createComponent(EspaceSalle);
  });

  it('should create', () => {
    expect(fixture.componentInstance).toBeTruthy();
  });
});