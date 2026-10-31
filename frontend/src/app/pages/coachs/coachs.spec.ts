import { ComponentFixture, TestBed } from '@angular/core/testing';

import { Coachs } from './coachs';

describe('Coachs', () => {
  let component: Coachs;
  let fixture: ComponentFixture<Coachs>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [Coachs],
    }).compileComponents();

    fixture = TestBed.createComponent(Coachs);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
