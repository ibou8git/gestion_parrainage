// src/pages/Dashboard.js
import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext.js';
import { Navigate } from 'react-router-dom';
import CandidatesList from '../components/CandidatesList.js';
import ParrainageForm from '../components/ParrainageForm.js';

const Dashboard = () => {
  const { isAuthenticated, logout } = useAuth();
  const [selectedCandidate, setSelectedCandidate] = useState(null);
  const [parrainageConfirmed, setParrainageConfirmed] = useState(false);

  // Rediriger vers la page de connexion si l'utilisateur n'est pas authentifié
  if (!isAuthenticated) {
    return <Navigate to="/login" />;
  }

  // Fonction appelée quand un candidat est sélectionné
  const handleCandidateSelect = (candidate) => {
    setSelectedCandidate(candidate);
    setParrainageConfirmed(false); // Réinitialiser la confirmation en cas de nouvelle sélection
  };

  // Fonction appelée lors de la confirmation du parrainage
  const handleParrainageConfirm = (candidate) => {
    setParrainageConfirmed(true);
    // Ici, tu pourras appeler une API pour enregistrer le parrainage
    alert(`Parrainage confirmé pour ${candidate.nom} !`);
  };

  return (
    <div style={{ textAlign: 'center', marginTop: '50px' }}>
      <h2>Bienvenue sur le Dashboard</h2>
      <p>Ceci est la page d'accueil après connexion.</p>
      <button onClick={logout} style={{ padding: '10px 20px', marginBottom: '20px' }}>
        Déconnexion
      </button>
      
      {/* Affiche la liste des candidats et passe la fonction de sélection */}
      <CandidatesList onCandidateSelect={handleCandidateSelect} />

      {/* Si un candidat est sélectionné et le parrainage n'est pas confirmé, afficher le formulaire de confirmation */}
      {selectedCandidate && !parrainageConfirmed && (
        <ParrainageForm 
          candidate={selectedCandidate} 
          onConfirm={handleParrainageConfirm}
        />
      )}

      {/* Si le parrainage est confirmé, afficher un message de confirmation */}
      {parrainageConfirmed && (
        <p style={{ marginTop: '20px', color: 'green' }}>
          Le parrainage pour {selectedCandidate.nom} a été confirmé !
        </p>
      )}
    </div>
  );
};

export default Dashboard;
