import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting } from '@angular/common/http/testing';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { provideRouter } from '@angular/router';
import { beforeEach, describe, expect, it } from 'vitest';
import { SalleDetail } from './salle-detail';

describe('SalleDetail', () => {
  let fixture: ComponentFixture<SalleDetail>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [SalleDetail],
      providers: [provideHttpClient(), provideHttpClientTesting(), provideRouter([])]
    }).compileComponents();

    fixture = TestBed.createComponent(SalleDetail);
  });

  it('should create', () => {
    expect(fixture.componentInstance).toBeTruthy();
  });
});