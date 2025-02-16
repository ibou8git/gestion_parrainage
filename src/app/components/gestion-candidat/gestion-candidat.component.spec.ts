import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GestionCandidatsComponent } from './gestion-candidat.component';

describe('GestionCandidatComponent', () => {
  let component: GestionCandidatsComponent;
  let fixture: ComponentFixture<GestionCandidatsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [GestionCandidatsComponent]
    })
      .compileComponents();

    fixture = TestBed.createComponent(GestionCandidatsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
