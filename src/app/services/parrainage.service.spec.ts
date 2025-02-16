import { TestBed } from '@angular/core/testing';

import { ParrainageService } from './parrainage.service';

describe('ParrainageService', () => {
  let service: ParrainageService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ParrainageService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
