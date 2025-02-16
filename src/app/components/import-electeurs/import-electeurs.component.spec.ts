import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ImportElecteursComponent } from './import-electeurs.component';

describe('ImportElecteursComponent', () => {
  let component: ImportElecteursComponent;
  let fixture: ComponentFixture<ImportElecteursComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ImportElecteursComponent]
    })
      .compileComponents();

    fixture = TestBed.createComponent(ImportElecteursComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
