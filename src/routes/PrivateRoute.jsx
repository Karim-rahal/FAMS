import { Navigate } from 'react-router-dom';

export default function PrivateRoute({ children, allowedRole }) {
  const token = localStorage.getItem('token');
  const role = localStorage.getItem('role');

  if (!token || role !== allowedRole) return <Navigate to="/login" />;
  return children;
}
