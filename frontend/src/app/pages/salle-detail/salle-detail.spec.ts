import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SalleDetail } from './salle-detail';

describe('SalleDetail', () => {
  let component: SalleDetail;
  let fixture: ComponentFixture<SalleDetail>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [SalleDetail],
    }).compileComponents();

    fixture = TestBed.createComponent(SalleDetail);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
