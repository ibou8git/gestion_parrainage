"use client";

import { useSession, signOut } from "next-auth/react"; 
import { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import { Line } from "react-chartjs-2";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
} from "chart.js";

// Enregistrer les composants nécessaires pour Chart.js
ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend);

export default function Dashboard() {
  const { data: session, status } = useSession(); 
  const router = useRouter();

  const [totalParrainages, setTotalParrainages] = useState<number>(258);
  const [parrainagesParJour, setParrainagesParJour] = useState<number[]>([50, 60, 70, 78]);
  const [data, setData] = useState<any>(null);

  // Redirige l'utilisateur vers /auth/login s'il n'est pas connecté
  useEffect(() => {
    if (status === "unauthenticated") {
      router.push("/auth/login");
    }
  }, [status, router]);

  // Initialisation des données du graphique
  useEffect(() => {
    setData({
      labels: ['Jour 1', 'Jour 2', 'Jour 3', 'Jour 4'],
      datasets: [
        {
          label: 'Parrainages',
          data: parrainagesParJour,
          borderColor: '#2D3748', // Bleu sombre
          backgroundColor: 'rgba(45, 55, 72, 0.2)', // Bleu clair
          tension: 0.4,
        },
      ],
    });
  }, [parrainagesParJour]);

  // Options du graphique
  const options = {
    responsive: true,
    plugins: {
      title: {
        display: true,
        text: 'Évolution des Parrainages',
      },
      tooltip: {
        mode: 'index',
        intersect: false,
      },
    },
  };

  // Fonction de déconnexion avec NextAuth
  const handleLogout = async () => {
    await signOut({ callbackUrl: "/auth/login" }); 
  };

  // Affichage pendant le chargement
  if (status === "loading") {
    return <div className="text-center mt-10 text-lg font-bold text-gray-600">Chargement...</div>;
  }

  // Si non connecté, ne pas afficher la page
  if (!session) {
    return null;
  }

  return (
    <main className="flex min-h-screen flex-col items-center justify-center bg-gradient-to-r from-blue-50 to-indigo-50 py-12">
      <h1 className="text-5xl font-extrabold text-blue-900 mb-8 drop-shadow-lg">
        📊 Tableau de Bord des Candidats
      </h1>

      {/* Section des statistiques */}
      <div className="bg-white shadow-2xl rounded-xl p-8 w-11/12 sm:w-96 mb-8">
        <h2 className="text-2xl font-semibold text-gray-800 mb-4">Statistiques de Parrainage</h2>
        <div className="mb-4">
          <p className="text-lg text-gray-700">
            Total de Parrainages : <strong className="text-blue-600">{totalParrainages}</strong>
          </p>
        </div>
        <div className="mb-4">
          <p className="text-lg text-gray-700">
            Parrainages des 4 derniers jours : <strong className="text-blue-600">{parrainagesParJour.join(", ")}</strong>
          </p>
        </div>
      </div>

      {/* Graphique */}
      <div className="w-11/12 sm:w-96 mb-8">
        {data ? <Line data={data} options={options} /> : <p>Chargement du graphique...</p>}
      </div>

      {/* Boutons */}
      <div className="flex space-x-4">
        <button 
          onClick={handleLogout}
          className="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-105"
        >
          🚪 Déconnexion
        </button>

        <button 
          onClick={() => { 
            setTotalParrainages(300); 
            setParrainagesParJour([100, 120, 130, 140]); 
          }}
          className="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-105"
        >
          🔄 Mettre à jour
        </button>
      </div>
    </main>
  );
}
