import Navbar from './components/common/Navbar';
import Footer from './components/common/Footer';
import AppRoutes from './routes';

export default function App() {
  return (
    <>
      <Navbar />
      <main className="min-h-screen container mx-auto p-4">
        <AppRoutes />
      </main>
      <Footer />
    </>
  );
}
