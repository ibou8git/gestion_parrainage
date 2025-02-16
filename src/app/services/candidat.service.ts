import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class CandidatService {

  private apiUrl = 'http://localhost:3000/api/candidats'; // Remplace par l'URL de ton backend

  constructor(private http: HttpClient) { }

  // Enregistrer un candidat
  enregistrerCandidat(candidat: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/enregistrer`, candidat);
  }

  // Récupérer la liste des candidats
  getCandidats(): Observable<any> {
    return this.http.get(`${this.apiUrl}/liste`);
  }

  // Modifier un candidat
  modifierCandidat(id: number, candidat: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/modifier/${id}`, candidat);
  }
}