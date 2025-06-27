import React from 'react';
import { Search, Menu, Globe, Database } from 'lucide-react';
import { Link } from 'react-router-dom';

const Header = () => {
  return (
    <header className="bg-white shadow-sm border-b">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center h-16">
          <div className="flex items-center">
            <Link to="/" className="flex-shrink-0 flex items-center">
              <Globe className="h-8 w-8 text-blue-700" />
              <div className="ml-3">
                <h1 className="text-xl font-bold text-gray-900">Portal Data Terbuka</h1>
                <p className="text-sm text-gray-600">Kabupaten Gorontalo</p>
              </div>
            </Link>
          </div>
          
          <nav className="hidden md:flex space-x-8">
            <a href="#beranda" className="text-gray-700 hover:text-blue-700 px-3 py-2 text-sm font-medium transition-colors">
              Beranda
            </a>
            <a href="#dataset" className="text-gray-700 hover:text-blue-700 px-3 py-2 text-sm font-medium transition-colors">
              Dataset
            </a>
            <a href="#kategori" className="text-gray-700 hover:text-blue-700 px-3 py-2 text-sm font-medium transition-colors">
              Kategori
            </a>
            <Link to="/about" className="text-gray-700 hover:text-blue-700 px-3 py-2 text-sm font-medium transition-colors">
              Tentang
            </Link>
            <a href="#kontak" className="text-gray-700 hover:text-blue-700 px-3 py-2 text-sm font-medium transition-colors">
              Kontak
            </a>
          </nav>
          
          <div className="md:hidden">
            <Menu className="h-6 w-6 text-gray-700" />
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;