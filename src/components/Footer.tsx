import React from 'react';
import { Globe, Mail, Phone, MapPin, Facebook, Twitter, Instagram, Youtube } from 'lucide-react';
import { Link } from 'react-router-dom';

const Footer = () => {
  return (
    <footer className="bg-gray-900 text-white py-12">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {/* Logo and Description */}
          <div className="lg:col-span-2">
            <div className="flex items-center mb-4">
              <Globe className="h-8 w-8 text-blue-400 mr-3" />
              <div>
                <h3 className="text-xl font-bold">Portal Data Terbuka</h3>
                <p className="text-gray-400">Kabupaten Gorontalo</p>
              </div>
            </div>
            <p className="text-gray-300 mb-6 leading-relaxed">
              Portal resmi untuk mengakses data publik Kabupaten Gorontalo. 
              Mendukung transparansi pemerintahan dan mendorong inovasi 
              melalui keterbukaan informasi.
            </p>
            <div className="flex space-x-4">
              <a href="#" className="text-gray-400 hover:text-white transition-colors">
                <Facebook className="h-6 w-6" />
              </a>
              <a href="#" className="text-gray-400 hover:text-white transition-colors">
                <Twitter className="h-6 w-6" />
              </a>
              <a href="#" className="text-gray-400 hover:text-white transition-colors">
                <Instagram className="h-6 w-6" />
              </a>
              <a href="#" className="text-gray-400 hover:text-white transition-colors">
                <Youtube className="h-6 w-6" />
              </a>
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="text-lg font-semibold mb-4">Tautan Cepat</h4>
            <ul className="space-y-2">
              <li>
                <a href="#beranda" className="text-gray-300 hover:text-white transition-colors">
                  Beranda
                </a>
              </li>
              <li>
                <a href="#dataset" className="text-gray-300 hover:text-white transition-colors">
                  Dataset
                </a>
              </li>
              <li>
                <a href="#kategori" className="text-gray-300 hover:text-white transition-colors">
                  Kategori
                </a>
              </li>
              <li>
                <Link to="/about" className="text-gray-300 hover:text-white transition-colors">
                  Tentang
                </Link>
              </li>
              <li>
                <a href="#" className="text-gray-300 hover:text-white transition-colors">
                  API Documentation
                </a>
              </li>
              <li>
                <a href="#" className="text-gray-300 hover:text-white transition-colors">
                  Panduan Penggunaan
                </a>
              </li>
            </ul>
          </div>

          {/* Contact Info */}
          <div id="kontak">
            <h4 className="text-lg font-semibold mb-4">Kontak Kami</h4>
            <div className="space-y-3">
              <div className="flex items-start">
                <MapPin className="h-5 w-5 text-gray-400 mr-3 flex-shrink-0 mt-1" />
                <p className="text-gray-300 text-sm">
                  Jl. 23 Januari No. 43<br />
                  Limboto, Gorontalo 96212
                </p>
              </div>
              <div className="flex items-center">
                <Phone className="h-5 w-5 text-gray-400 mr-3" />
                <p className="text-gray-300 text-sm">(0435) 881234</p>
              </div>
              <div className="flex items-center">
                <Mail className="h-5 w-5 text-gray-400 mr-3" />
                <p className="text-gray-300 text-sm">opendata@gorontalokab.go.id</p>
              </div>
            </div>
          </div>
        </div>

        <div className="border-t border-gray-800 mt-8 pt-8">
          <div className="flex flex-col md:flex-row justify-between items-center">
            <p className="text-gray-400 text-sm mb-4 md:mb-0">
              © 2024 Pemerintah Kabupaten Gorontalo. Hak cipta dilindungi undang-undang.
            </p>
            <div className="flex space-x-6 text-sm text-gray-400">
              <a href="#" className="hover:text-white transition-colors">
                Kebijakan Privasi
              </a>
              <a href="#" className="hover:text-white transition-colors">
                Syarat & Ketentuan
              </a>
              <a href="#" className="hover:text-white transition-colors">
                Disclaimer
              </a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;