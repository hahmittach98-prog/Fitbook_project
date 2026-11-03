import { ComponentFixture, TestBed } from '@angular/core/testing';

import { Etoile } from './etoile';

describe('Etoile', () => {
  let component: Etoile;
  let fixture: ComponentFixture<Etoile>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [Etoile],
    }).compileComponents();

    fixture = TestBed.createComponent(Etoile);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
