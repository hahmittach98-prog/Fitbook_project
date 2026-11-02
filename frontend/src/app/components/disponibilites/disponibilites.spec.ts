import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting } from '@angular/common/http/testing';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { beforeEach, describe, expect, it } from 'vitest';
import { Disponibilites } from './disponibilites';

describe('Disponibilites', () => {
  let fixture: ComponentFixture<Disponibilites>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [Disponibilites],
      providers: [provideHttpClient(), provideHttpClientTesting()]
    }).compileComponents();

    fixture = TestBed.createComponent(Disponibilites);
  });

  it('shows a message when there is no availability', () => {
    // Act
    fixture.detectChanges();

    // Assert
    expect(fixture.nativeElement.textContent).toContain('Aucune disponibilité');
  });

  it('shows the 7 days of the week in the form', () => {
    fixture.detectChanges();

    const $options = fixture.nativeElement.querySelectorAll('select option');
    expect($options.length).toBe(7);
  });
});