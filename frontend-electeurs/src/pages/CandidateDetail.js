import React from 'react';
import { useParams, useNavigate } from 'react-router-dom';

const candidates = [
  {
    id: 1,
    nom: "Maïrame Niang",
    slogan: "AND SUKKALI THIAROYE",
    couleurParti: "blue",
    photo: "https://via.placeholder.com/150?text=Mairame+Niang",
    description: "Maïrame Niang propose un programme axé sur l'économie et l'éducation."
  },
  {
    id: 2,
    nom: "Ousmane Niang",
    slogan: "Pour un changement durable",
    couleurParti: "green",
    photo: "https://via.placeholder.com/150?text=Ousmane+Niang",
    description: "Ousmane Niang met l'accent sur l'environnement et les énergies renouvelables."
  },
  {
    id: 3,
    nom: "SoukeyNA Niang",
    slogan: "Sama gokh sama yitee",
    couleurParti: "red",
    photo: "https://via.placeholder.com/150?text=SoukeyNA+Niang",
    description: "SoukeyNA Niang prône la solidarité et le progrès social."
  }
];

const CandidateDetail = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const candidate = candidates.find(c => c.id === parseInt(id));

  if (!candidate) {
    return <p>Candidat introuvable.</p>;
  }

  return (
    <div style={{ textAlign: 'center', marginTop: '20px' }}>
      <h2>Détails du Candidat</h2>
      <img src={candidate.photo} alt={candidate.nom} style={{ width: '150px', borderRadius: '50%' }} />
      <h3>{candidate.nom}</h3>
      <p><strong>Slogan:</strong> {candidate.slogan}</p>
      <p><strong>Description:</strong> {candidate.description}</p>
      <button onClick={() => navigate(-1)} style={{ padding: '8px 16px', marginTop: '10px' }}>
        Retour
      </button>
    </div>
  );
};

export default CandidateDetail;
