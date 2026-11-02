import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EspaceCoach } from './espace-coach';

describe('EspaceCoach', () => {
  let component: EspaceCoach;
  let fixture: ComponentFixture<EspaceCoach>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [EspaceCoach],
    }).compileComponents();

    fixture = TestBed.createComponent(EspaceCoach);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
