import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  imports: [RouterOutlet],
  standalone: true
})
export class AppComponent {
  activeTab: string = 'accueil'; // Onglet actif par défaut

  // Changer l'onglet actif
  setActiveTab(tab: string) {
    this.activeTab = tab;
  }
}