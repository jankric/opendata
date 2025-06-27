import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';

// Public components
import Header from './components/Header';
import Hero from './components/Hero';
import Categories from './components/Categories';
import FeaturedDatasets from './components/FeaturedDatasets';
import Statistics from './components/Statistics';
import About from './components/About';
import Footer from './components/Footer';

// Pages
import AboutPage from './pages/AboutPage';

function PublicApp() {
  return (
    <div className="min-h-screen bg-white">
      <Header />
      <main>
        <Hero />
        <Categories />
        <FeaturedDatasets />
        <Statistics />
        <About />
      </main>
      <Footer />
    </div>
  );
}

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/about" element={<AboutPage />} />
        <Route path="/" element={<PublicApp />} />
        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </Router>
  );
}

export default App;