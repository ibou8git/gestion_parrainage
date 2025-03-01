// src/pages/Login.js
import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { Link } from 'react-router-dom';

const Login = () => {
  const [electorCard, setElectorCard] = useState('');
  const [idCard, setIdCard] = useState('');
  const { login } = useAuth(); // Utilisation du contexte d'authentification

  const handleSubmit = (e) => {
    e.preventDefault();
    console.log('Numéro de carte d’électeur:', electorCard);
    console.log('Numéro de carte d’identité:', idCard);
    // Simuler une connexion réussie
    login({ electorCard, idCard });
  };

  return (
    <div style={{ maxWidth: '400px', margin: '50px auto', textAlign: 'center' }}>
      <h2>Connexion Électeur</h2>
      <form onSubmit={handleSubmit}>
        <div style={{ marginBottom: '10px' }}>
          <label>
            Numéro de carte d’électeur:
            <input
              type="text"
              value={electorCard}
              onChange={(e) => setElectorCard(e.target.value)}
              required
              style={{ width: '100%', padding: '8px', marginTop: '5px' }}
            />
          </label>
        </div>
        <div style={{ marginBottom: '10px' }}>
          <label>
            Numéro de carte d’identité nationale:
            <input
              type="text"
              value={idCard}
              onChange={(e) => setIdCard(e.target.value)}
              required
              style={{ width: '100%', padding: '8px', marginTop: '5px' }}
            />
          </label>
        </div>
        <button type="submit" style={{ padding: '10px 20px' }}>Se connecter</button>
      </form>
      <p style={{ marginTop: '20px' }}>
        Pas de compte ? <Link to="/register">Créer un compte</Link>
      </p>
    </div>
  );
};

export default Login;
