import { useState } from 'react';
import { loginUser } from '../../services/api';
import { useNavigate } from 'react-router-dom';

export default function Login() {
  const [form, setForm] = useState({ email: '', password: '' });
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const res = await loginUser(form);
      localStorage.setItem('token', res.data.token);
      localStorage.setItem('role', res.data.role);

      if (res.data.role === 'player') navigate('/dashboard/player');
      else if (res.data.role === 'coach') navigate('/dashboard/coach');
      else navigate('/');
    } catch (err) {
      setError(err.response?.data?.msg || 'Login failed');
    }
  };

  return (
    <div className="max-w-md mx-auto my-20 p-8 bg-white rounded shadow">
      <h2 className="text-2xl font-bold mb-4">Login</h2>
      <form onSubmit={handleSubmit} className="flex flex-col gap-4">
        <input name="email" type="email" placeholder="Email" onChange={handleChange} className="border p-2 rounded" required />
        <input name="password" type="password" placeholder="Password" onChange={handleChange} className="border p-2 rounded" required />
        {error && <p className="text-red-500">{error}</p>}
        <button type="submit" className="bg-[#003b2f] text-white p-2 rounded hover:bg-[#002b2f]">Login</button>
      </form>
    </div>
  );
}
