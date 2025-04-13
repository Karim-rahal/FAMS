import React from 'react';
import Navbar from './components/common/Navbar';
import Footer from './components/common/Footer';
import AppRoutes from './routes';

function App() {
  return (
    <>
      <Navbar />
      <main className="container mx-auto min-h-screen p-4">
        <AppRoutes />
      </main>
      <Footer />
    </>
  );
}

export default App;
