import { Component, OnInit } from '@angular/core';
import { ParrainageService } from '../../services/parrainage.service';
import { Chart } from 'chart.js';



@Component({
  selector: 'app-monitoring',
  templateUrl: './monitoring.component.html',
  styleUrls: ['./monitoring.component.css'],
})
export class MonitoringComponent {

  stats: any[] = [];
  chart: any;
  isLoading = true;
  errorMessage = '';

  constructor(private parrainageService: ParrainageService) { }

  ngOnInit(): void {
    this.loadStats();
  }

  loadStats() {
    this.isLoading = true;
    this.errorMessage = '';

    this.parrainageService.getStats().subscribe(
      (data) => {
        this.stats = data;
        this.isLoading = false;
        this.renderChart();
      },
      (error) => {
        this.isLoading = false;
        this.errorMessage = 'Erreur lors du chargement des statistiques.';
        console.error(error);
      }
    );
  }

  renderChart() {
    if (this.chart) {
      this.chart.destroy();
    }

    const labels = this.stats.map((stat) => stat.candidat);
    const data = this.stats.map((stat) => stat.parrainages);

    this.chart = new Chart('parrainageChart', {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Parrainages',
            data: data,
            backgroundColor: ['#1abc9c', '#3498db', '#e74c3c', '#f39c12', '#9b59b6'],
            borderColor: '#34495e',
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  }


}
