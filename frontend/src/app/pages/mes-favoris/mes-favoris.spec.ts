import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MesFavoris } from './mes-favoris';

describe('MesFavoris', () => {
  let component: MesFavoris;
  let fixture: ComponentFixture<MesFavoris>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [MesFavoris],
    }).compileComponents();

    fixture = TestBed.createComponent(MesFavoris);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
