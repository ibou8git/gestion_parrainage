// src/pages/Register.js
import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const Register = () => {
  const [electorCard, setElectorCard] = useState('');
  const [idCard, setIdCard] = useState('');
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = (e) => {
    e.preventDefault();
    console.log('Création de compte avec :', { electorCard, idCard, name, email });
    // Simuler l'inscription en stockant les informations dans le contexte via login()
    login({ electorCard, idCard, name, email });
    // Rediriger vers le Dashboard après l'inscription
    navigate('/');
  };

  return (
    <div style={{ maxWidth: '400px', margin: '50px auto', textAlign: 'center' }}>
      <h2>Création de compte pour le parrainage</h2>
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
        <div style={{ marginBottom: '10px' }}>
          <label>
            Nom:
            <input
              type="text"
              value={name}
              onChange={(e) => setName(e.target.value)}
              required
              style={{ width: '100%', padding: '8px', marginTop: '5px' }}
            />
          </label>
        </div>
        <div style={{ marginBottom: '10px' }}>
          <label>
            Email:
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              style={{ width: '100%', padding: '8px', marginTop: '5px' }}
            />
          </label>
        </div>
        <button type="submit" style={{ padding: '10px 20px' }}>Créer mon compte</button>
      </form>
    </div>
  );
};

export default Register;
