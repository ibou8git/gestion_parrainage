import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { RouterModule } from '@angular/router';


@Component({
  selector: 'app-accueil',
  standalone: true,
  imports: [FormsModule, CommonModule, HttpClientModule, RouterModule],
  styleUrls: ['./accueil.component.css'],
  templateUrl: './accueil.component.html'
})
export class AccueilComponent {

  constructor(
    private http: HttpClient
  ) { }

}
