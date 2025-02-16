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
export class GestionCandidatsComponent {

}
