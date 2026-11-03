import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting } from '@angular/common/http/testing';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { beforeEach, describe, expect, it } from 'vitest';
import { ReservationsRecues } from './reservations-recues';

describe('ReservationsRecues', () => {
  let fixture: ComponentFixture<ReservationsRecues>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ReservationsRecues],
      providers: [provideHttpClient(), provideHttpClientTesting()]
    }).compileComponents();

    fixture = TestBed.createComponent(ReservationsRecues);
  });

  it('should create', () => {
    expect(fixture.componentInstance).toBeTruthy();
  });
});