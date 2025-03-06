// src/components/ParrainageForm.js
import React, { useState } from 'react';

const ParrainageForm = ({ candidate, onConfirm }) => {
  const [code, setCode] = useState('');
  // Simulons la génération d'un code de confirmation, par exemple 5 chiffres
  const generatedCode = "12345"; // En vrai, ce code devrait être envoyé par SMS/email

  const handleSubmit = (e) => {
    e.preventDefault();
    if (code === generatedCode) {
      onConfirm(candidate);
    } else {
      alert("Le code de confirmation est incorrect !");
    }
  };

  return (
    <div style={{ marginTop: '30px', textAlign: 'center' }}>
      <h3>Confirmer le parrainage pour {candidate.nom}</h3>
      <p>Un code de confirmation vous a été envoyé (code simulé : {generatedCode})</p>
      <form onSubmit={handleSubmit}>
        <input 
          type="text"
          value={code}
          onChange={(e) => setCode(e.target.value)}
          placeholder="Entrez le code de confirmation"
          required
          style={{ padding: '8px', marginRight: '10px' }}
        />
        <button type="submit" style={{ padding: '8px 16px' }}>Valider</button>
      </form>
    </div>
  );
};

export default ParrainageForm;
