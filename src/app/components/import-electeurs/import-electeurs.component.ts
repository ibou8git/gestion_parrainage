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

}