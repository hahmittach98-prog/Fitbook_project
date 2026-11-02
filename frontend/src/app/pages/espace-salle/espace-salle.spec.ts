import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EspaceSalle } from './espace-salle';

describe('EspaceSalle', () => {
  let component: EspaceSalle;
  let fixture: ComponentFixture<EspaceSalle>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [EspaceSalle],
    }).compileComponents();

    fixture = TestBed.createComponent(EspaceSalle);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
