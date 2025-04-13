import { useState, useEffect } from 'react';
import { registerUser } from '../../services/api';
import { useNavigate } from 'react-router-dom';

export default function Register() {
  const [form, setForm] = useState({ name: '', email: '', password: '', role: 'player' });
  const [error, setError] = useState('');
  const navigate = useNavigate();

  useEffect(() => {
    const token = localStorage.getItem('token');
    const role = localStorage.getItem('role');

    if (token && role) {
      if (role === 'player') navigate('/dashboard/player');
      else if (role === 'coach') navigate('/dashboard/coach');
      else if (role === 'admin') navigate('/dashboard/admin');
    }
  }, []);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      // Force role to either "player" or "coach" only (safety check)
      const safeRole = form.role === 'coach' ? 'coach' : 'player';
      await registerUser({ ...form, role: safeRole });
      navigate('/login');
    } catch (err) {
      setError(err.response?.data?.msg || 'Registration failed');
    }
  };

  return (
    <div className="max-w-md mx-auto my-20 p-8 bg-white rounded shadow">
      <h2 className="text-2xl font-bold mb-4">Register</h2>
      <form onSubmit={handleSubmit} className="flex flex-col gap-4">
        <input
          name="name"
          type="text"
          placeholder="Name"
          onChange={handleChange}
          className="border p-2 rounded"
          required
        />
        <input
          name="email"
          type="email"
          placeholder="Email"
          onChange={handleChange}
          className="border p-2 rounded"
          required
        />
        <input
          name="password"
          type="password"
          placeholder="Password"
          onChange={handleChange}
          className="border p-2 rounded"
          required
        />
        <select
          name="role"
          onChange={handleChange}
          className="border p-2 rounded"
          value={form.role}
        >
          <option value="player">Player</option>
          <option value="coach">Coach</option>
        </select>
        {error && <p className="text-red-500">{error}</p>}
        <button
          type="submit"
          className="bg-[#003b2f] text-white p-2 rounded hover:bg-[#002b2f]"
        >
          Register
        </button>
      </form>
    </div>
  );
}
