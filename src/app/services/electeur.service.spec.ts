import { TestBed } from '@angular/core/testing';

import { ElecteurService } from './electeur.service';

describe('ElecteurService', () => {
  let service: ElecteurService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ElecteurService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
