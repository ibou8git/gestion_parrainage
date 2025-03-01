import { createContext, useContext, useState } from "react";
import { useNavigate } from "react-router-dom";

// Création du contexte d'authentification
const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const navigate = useNavigate();

  // Connexion (simulée pour l’instant)
  const login = () => {
    setIsAuthenticated(true);
    navigate("/"); // Redirige vers le Dashboard
  };

  // Déconnexion
  const logout = () => {
    setIsAuthenticated(false);
    navigate("/login"); // Redirige vers la page de connexion
  };

  return (
    <AuthContext.Provider value={{ isAuthenticated, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

// Hook personnalisé pour utiliser l’authentification
export const useAuth = () => {
  return useContext(AuthContext);
};
