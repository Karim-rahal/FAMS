import { Link, useNavigate } from 'react-router-dom';
import logo from '../../assets/logo.png';

export default function Navbar() {
  const token = localStorage.getItem('token');
  const role = localStorage.getItem('role');
  const navigate = useNavigate();

  const handleLogout = () => {
    localStorage.removeItem('token');
    localStorage.removeItem('role');
    navigate('/login');
  };

  const goToDashboard = () => {
    if (role === 'player') navigate('/dashboard/player');
    else if (role === 'coach') navigate('/dashboard/coach');
    else if (role === 'admin') navigate('/dashboard/admin');
  };

  return (
    <nav className="bg-[#003b2f] text-white px-6 py-4 shadow-md">
      <div className="container mx-auto flex justify-between items-center">
        {/* Logo + Brand */}
        <div className="flex items-center gap-3">
          <img src={logo} alt="FAMS Logo" className="h-10 w-auto" />
          <Link to="/" className="text-2xl font-bold text-white">FAMS</Link>
        </div>

        {/* Navigation Links */}
        <ul className="flex items-center gap-6 text-white font-medium">
          <li><Link to="/" className="hover:text-gray-300">Home</Link></li> {/* Always visible */}

          {!token && (
            <>
              <li><Link to="/login" className="hover:text-gray-300">Login</Link></li>
              <li><Link to="/register" className="hover:text-gray-300">Register</Link></li>
            </>
          )}

          {token && (
            <>
              <li>
                <button
                  onClick={goToDashboard}
                  className="capitalize text-yellow-300 hover:underline"
                  title="Go to your dashboard"
                >
                  {role}
                </button>
              </li>
              <li>
                <button
                  onClick={handleLogout}
                  className="bg-red-600 px-4 py-1 rounded hover:bg-red-700"
                >
                  Logout
                </button>
              </li>
            </>
          )}
        </ul>
      </div>
    </nav>
  );
}
