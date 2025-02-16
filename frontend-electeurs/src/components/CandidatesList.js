import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

const CandidatesList = ({ onCandidateSelect }) => {
  const [candidates, setCandidates] = useState([]);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    const candidats = [
      { id: 1, nom: "Maïrame Niang", slogan: "AND SUKKALI THIAROYE", couleurParti: "blue", photo: "https://via.placeholder.com/150?text=Mairame+Niang" },
      { id: 2, nom: "Ousmane Niang", slogan: "Pour un changement durable", couleurParti: "green", photo: "https://via.placeholder.com/150?text=Ousmane+Niang" },
      { id: 3, nom: "SoukeyNA Niang", slogan: "Sama gokh sama yitee", couleurParti: "red", photo: "https://via.placeholder.com/150?text=SoukeyNA+Niang" }
    ];
    setCandidates(candidats);
    setLoading(false);
  }, []);

  if (loading) {
    return <p>Chargement des candidats...</p>;
  }

  return (
    <div>
      <h2>Liste des Candidats</h2>
      {candidates.length === 0 ? (
        <p>Aucun candidat trouvé.</p>
      ) : (
        <ul style={{ listStyle: 'none', padding: 0 }}>
          {candidates.map((candidate) => (
            <li 
              key={candidate.id} 
              style={{ 
                marginBottom: '20px', 
                border: `2px solid ${candidate.couleurParti}`, 
                padding: '10px', 
                borderRadius: '8px'
              }}
            >
              <h3>{candidate.nom}</h3>
              <img 
                src={candidate.photo} 
                alt={candidate.nom} 
                style={{ width: '100px', borderRadius: '50%' }} 
              />
              <p><strong>Slogan:</strong> {candidate.slogan}</p>
              <button 
                onClick={() => onCandidateSelect(candidate)}
                style={{ padding: '8px 16px', marginRight: '10px' }}
              >
                Choisir ce candidat
              </button>
              <button 
                onClick={() => navigate(`/candidate/${candidate.id}`)}
                style={{ padding: '8px 16px', backgroundColor: 'lightgray' }}
              >
                Voir détails
              </button>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
};

export default CandidatesList;
