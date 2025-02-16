import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { ElecteurService } from '../../services/electeur.service';

@Component({
  selector: 'app-import-electeurs',
  templateUrl: './import-electeurs.component.html',
  styleUrls: ['./import-electeurs.component.css'],
  standalone: true,
  imports: [FormsModule, CommonModule]
})
export class ImportElecteurComponent {
  file: File | null = null;
  checksum: string = '';
  importErrors: any[] = [];
  isFileValid: boolean = false;
  isFileInvalid: boolean = false;
  isChecksumInvalid: boolean = false;
  isUploading: boolean = false;
  uploadProgress: number = 0;

  constructor(
    private electeurService: ElecteurService,
    private http: HttpClient
  ) { }

  onFileChange(event: any) {
    this.file = event.target.files[0];
    this.validateFile();
  }

  onSubmit() {
    // if (this.file && this.checksum) {
    //this.electeurService.uploadFile(this.file, this.checksum).subscribe(
    // (response) => {
    // console.log('Fichier importé avec succès', response);
    //this.isFileValid = true;
    // this.loadImportErrors();
    //},
    //(error) => {
    //console.error('Erreur lors de l\'importation', error);
    //}
    //    );
    // }
  }

  validateFile() {
    if (this.file) {
      // Simuler la validation du fichier
      const fileExtension = this.file.name.split('.').pop();
      this.isFileInvalid = fileExtension !== 'csv';
      if (this.isFileInvalid) {
        return;
      }
      // Si le fichier est un CSV, on le considère valide
      this.isFileValid = true;
      this.validateChecksum();
    }
  }


  validateChecksum() {
    if (this.checksum) {
      // Simulation de la validation du checksum (exemple simple)
      const fileChecksum = this.calculateChecksum(this.file);
      this.isChecksumInvalid = fileChecksum !== this.checksum;
    }
  }

  calculateChecksum(file: File | null): string {
    if (!file) return '';
    // Calcul du checksum pour simulation
    return 'simulatedChecksum';
  }


  loadImportErrors() {
    //   this.electeurService.getImportErrors().subscribe(
    //     (errors) => {
    //       this.importErrors = errors;
    //     },
    //     (error) => {
    //       console.error('Erreur lors du chargement des erreurs', error);
    //     }
    //   );
  }

  validateImport() {
    //this.electeurService.validateElecteurs().subscribe(
    // (response) => {
    //  console.log('Importation validée avec succès', response);
    //  this.isFileValid = false;
    //},
    //(error) => {
    // console.error('Erreur lors de la validation', error);
    //}
    //);
    alert('Importation validée');

  }

  importFile() {
    this.isUploading = true;
    const formData = new FormData();
    formData.append('file', this.file!);

    this.http.post('http://localhost:3000/import', formData, {
      headers: {
        'Checksum': this.checksum
      },
      observe: 'events',
      reportProgress: true
    }).subscribe({
      next: (event: any) => {
        if (event.type === 1) { // Progress event
          this.uploadProgress = Math.round((100 * event.loaded) / event.total!);
        } else if (event.type === 4) { // Complete event
          this.uploadProgress = 100;
          this.importErrors = event.body.errors || [];
          this.isUploading = false;
        }
      },
      error: () => {
        this.isUploading = false;
        this.isFileInvalid = true;
        this.importErrors = [{ cin: '', electeurId: '', probleme: 'Échec de l\'importation' }];
      }
    });
  }
}