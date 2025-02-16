import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class ElecteurService {

  private apiUrl = 'http://localhost:3000/api/electeurs'; // Remplace par l'URL de ton backend

  constructor(private http: HttpClient) { }

  //Upload du fichier CSV et vérification du checksum
  uploadFile(file: File, checksum: string): Observable<any> {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('checksum', checksum);

    return this.http.post(`${this.apiUrl}/upload`, formData);
  }

  //Validation finale des électeurs
  validateElecteurs(): Observable<any> {
    return this.http.post(`${this.apiUrl}/validate`, {});
  }

  //Récupérer les erreurs d'importation
  getImportErrors(): Observable<any> {
    return this.http.get(`${this.apiUrl}/errors`);
  }

}