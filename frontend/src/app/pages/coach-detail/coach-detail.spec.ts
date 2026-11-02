import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting } from '@angular/common/http/testing';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { provideRouter } from '@angular/router';
import { beforeEach, describe, expect, it } from 'vitest';
import { CoachDetail } from './coach-detail';

describe('CoachDetail', () => {
  let fixture: ComponentFixture<CoachDetail>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CoachDetail],
      providers: [provideHttpClient(), provideHttpClientTesting(), provideRouter([])]
    }).compileComponents();

    fixture = TestBed.createComponent(CoachDetail);
  });

  it('should create', () => {
    expect(fixture.componentInstance).toBeTruthy();
  });
});