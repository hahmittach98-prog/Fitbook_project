import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting } from '@angular/common/http/testing';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { provideRouter } from '@angular/router';
import { beforeEach, describe, expect, it } from 'vitest';
import { EspaceCoach } from './espace-coach';

describe('EspaceCoach', () => {
  let fixture: ComponentFixture<EspaceCoach>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [EspaceCoach],
      providers: [provideHttpClient(), provideHttpClientTesting(), provideRouter([])]
    }).compileComponents();

    fixture = TestBed.createComponent(EspaceCoach);
  });

  it('should create', () => {
    expect(fixture.componentInstance).toBeTruthy();
  });
});