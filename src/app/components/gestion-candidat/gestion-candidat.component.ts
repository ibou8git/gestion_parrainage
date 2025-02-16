import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-candidats',
  templateUrl: './gestion-candidat.component.html',
  styleUrls: ['./gestion-candidat.component.css'],
  standalone: true,
  imports: [FormsModule, CommonModule]
})
export class GestionCandidatsComponent implements OnInit {

  candidats: any[] = [];
  nouveauCandidat = {
    nom: '',
    prenom: '',
    parti: '',
    slogan: '',
    isEligible: true
  };
  apiUrl = 'http://localhost:3000/candidats'; // Remplace par l'URL de ton backend
  successMessage: string = '';
  errorMessage: string = '';

  constructor(private http: HttpClient) { }

  ngOnInit() {
    this.chargerCandidats();
  }

  chargerCandidats() {
    this.http.get<any[]>(this.apiUrl).subscribe(
      data => {
        this.candidats = data;
      },
      error => {
        this.errorMessage = 'Erreur lors du chargement des candidats';
        console.error('Erreur de chargement des candidats:', error);
      }
    );
  }

  enregistrerCandidat() {
    if (!this.nouveauCandidat.nom || !this.nouveauCandidat.prenom) {
      this.errorMessage = 'Tous les champs doivent être remplis';
      return;
    }

    this.http.post<any>(this.apiUrl, this.nouveauCandidat).subscribe(
      response => {
        this.candidats.push(response); // Ajouter le candidat à la liste locale
        this.successMessage = 'Candidat ajouté avec succès!';
        this.nouveauCandidat = { nom: '', prenom: '', parti: '', slogan: '', isEligible: true }; // Réinitialiser le formulaire
      },
      error => {
        this.errorMessage = 'Erreur lors de l\'ajout du candidat';
        console.error('Erreur d\'ajout du candidat:', error);
      }
    );
  }

  // Fonction pour effacer les messages d'erreur et de succès
  clearMessages() {
    this.successMessage = '';
    this.errorMessage = '';
  }

}
