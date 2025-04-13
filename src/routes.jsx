import { Routes, Route } from 'react-router-dom';
import Home from './pages/Home';
import Login from './components/Auth/Login';
import Register from './components/Auth/Register';
import PlayerDashboard from './components/Dashboard/PlayerDashboard';
import CoachDashboard from './components/Dashboard/CoachDashboard';
import AdminDashboard from './components/Dashboard/AdminDashboard';
import NotFound from './pages/NotFound';
import PrivateRoute from './routes/PrivateRoute';

export default function AppRoutes() {
  return (
    <Routes>
      <Route path="/" element={<Home />} />
      <Route path="/login" element={<Login />} />
      <Route path="/register" element={<Register />} />

      <Route path="/dashboard/player" element={
        <PrivateRoute allowedRole="player"><PlayerDashboard /></PrivateRoute>
      } />
      <Route path="/dashboard/coach" element={
        <PrivateRoute allowedRole="coach"><CoachDashboard /></PrivateRoute>
      } />
      <Route path="/dashboard/admin" element={
        <PrivateRoute allowedRole="admin"><AdminDashboard /></PrivateRoute>
      } />

      <Route path="*" element={<NotFound />} />
    </Routes>
  );
}
