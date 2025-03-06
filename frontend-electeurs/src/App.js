import { Routes, Route } from 'react-router-dom';
import Dashboard from './pages/Dashboard.js';
import Login from './pages/Login';
import Register from './pages/Register.js';
import CandidateDetail from './pages/CandidateDetail.js';
import { AuthProvider } from './context/AuthContext';

function App() {
  return (
    <AuthProvider>
      <Routes>
        <Route path="/" element={<Dashboard />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/candidate/:id" element={<CandidateDetail />} />
      </Routes>
    </AuthProvider>
  );
}

export default App;
