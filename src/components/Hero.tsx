import React, { useState, useEffect } from 'react';
import { Search, Download, TrendingUp, Users, ChevronLeft, ChevronRight } from 'lucide-react';

const Hero = () => {
  const [searchQuery, setSearchQuery] = useState('');
  const [currentSlide, setCurrentSlide] = useState(0);

  // Array gambar untuk slider
  const sliderImages = [
    {
      url: 'https://images.pexels.com/photos/3184291/pexels-photo-3184291.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop',
      title: 'Data Kependudukan',
      description: 'Akses data demografis terlengkap'
    },
    {
      url: 'https://images.pexels.com/photos/3184338/pexels-photo-3184338.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop',
      title: 'Ekonomi & PDRB',
      description: 'Indikator ekonomi real-time'
    },
    {
      url: 'https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop',
      title: 'Infrastruktur',
      description: 'Data pembangunan dan fasilitas'
    },
    {
      url: 'https://images.pexels.com/photos/3184317/pexels-photo-3184317.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop',
      title: 'Kesehatan',
      description: 'Layanan kesehatan masyarakat'
    },
    {
      url: 'https://images.pexels.com/photos/3184360/pexels-photo-3184360.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop',
      title: 'Pendidikan',
      description: 'Data sekolah dan pendidikan'
    }
  ];

  // Auto slide setiap 4 detik
  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % sliderImages.length);
    }, 4000);

    return () => clearInterval(interval);
  }, [sliderImages.length]);

  const nextSlide = () => {
    setCurrentSlide((prev) => (prev + 1) % sliderImages.length);
  };

  const prevSlide = () => {
    setCurrentSlide((prev) => (prev - 1 + sliderImages.length) % sliderImages.length);
  };

  const goToSlide = (index: number) => {
    setCurrentSlide(index);
  };

  return (
    <section className="relative bg-gradient-to-br from-blue-700 via-blue-800 to-blue-900 text-white py-16 overflow-hidden">
      {/* Background Image Slider */}
      <div className="absolute inset-0 z-0">
        <div className="relative w-full h-full">
          {sliderImages.map((image, index) => (
            <div
              key={index}
              className={`absolute inset-0 transition-all duration-1000 ease-in-out ${
                index === currentSlide 
                  ? 'opacity-60 transform translate-x-0' 
                  : index === (currentSlide - 1 + sliderImages.length) % sliderImages.length
                  ? 'opacity-0 transform -translate-x-full'
                  : 'opacity-0 transform translate-x-full'
              }`}
            >
              <img
                src={image.url}
                alt={image.title}
                className="w-full h-full object-cover"
              />
              <div className="absolute inset-0 bg-gradient-to-br from-blue-900/50 via-blue-800/40 to-blue-700/30"></div>
            </div>
          ))}
        </div>

        {/* Slider Controls */}
        <button
          onClick={prevSlide}
          className="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 p-3 bg-white/20 hover:bg-white/30 rounded-full backdrop-blur-sm transition-all duration-300 group"
        >
          <ChevronLeft className="h-6 w-6 text-white group-hover:scale-110 transition-transform" />
        </button>
        
        <button
          onClick={nextSlide}
          className="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 p-3 bg-white/20 hover:bg-white/30 rounded-full backdrop-blur-sm transition-all duration-300 group"
        >
          <ChevronRight className="h-6 w-6 text-white group-hover:scale-110 transition-transform" />
        </button>

        {/* Slide Indicators */}
        <div className="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-10 flex space-x-3">
          {sliderImages.map((_, index) => (
            <button
              key={index}
              onClick={() => goToSlide(index)}
              className={`w-3 h-3 rounded-full transition-all duration-300 ${
                index === currentSlide 
                  ? 'bg-white scale-125' 
                  : 'bg-white/50 hover:bg-white/75'
              }`}
            />
          ))}
        </div>

        {/* Slide Info */}
        <div className="absolute bottom-20 left-6 z-10 max-w-sm">
          <div className="bg-white/15 backdrop-blur-md rounded-xl p-4 border border-white/30">
            <h3 className="text-lg font-bold text-white mb-1">
              {sliderImages[currentSlide].title}
            </h3>
            <p className="text-blue-100 text-sm">
              {sliderImages[currentSlide].description}
            </p>
          </div>
        </div>
      </div>

      {/* Content */}
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <h1 className="text-4xl md:text-6xl font-bold mb-6 leading-tight">
            Portal Data Terbuka
            <span className="block text-blue-200">Kabupaten Gorontalo</span>
          </h1>
          <p className="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto leading-relaxed">
            Akses mudah ke data publik Kabupaten Gorontalo. Mendukung transparansi, 
            inovasi, dan pembangunan berkelanjutan melalui keterbukaan informasi.
          </p>
        </div>

        <div className="max-w-2xl mx-auto mb-12">
          <div className="relative">
            <input
              type="text"
              placeholder="Cari dataset, kategori, atau topik..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="w-full px-6 py-4 pl-14 text-gray-900 bg-white/95 backdrop-blur-sm rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-300 text-lg border border-white/20"
            />
            <Search className="absolute left-4 top-1/2 transform -translate-y-1/2 h-6 w-6 text-gray-400" />
            <button className="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
              Cari
            </button>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div className="text-center p-6 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20 hover:bg-white/15 transition-all duration-300 group">
            <Download className="h-12 w-12 text-blue-200 mx-auto mb-4 group-hover:scale-110 transition-transform" />
            <h3 className="text-2xl font-bold mb-2">150+</h3>
            <p className="text-blue-200">Dataset Tersedia</p>
          </div>
          <div className="text-center p-6 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20 hover:bg-white/15 transition-all duration-300 group">
            <Users className="h-12 w-12 text-blue-200 mx-auto mb-4 group-hover:scale-110 transition-transform" />
            <h3 className="text-2xl font-bold mb-2">5,000+</h3>
            <p className="text-blue-200">Pengguna Aktif</p>
          </div>
          <div className="text-center p-6 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20 hover:bg-white/15 transition-all duration-300 group">
            <TrendingUp className="h-12 w-12 text-blue-200 mx-auto mb-4 group-hover:scale-110 transition-transform" />
            <h3 className="text-2xl font-bold mb-2">25,000+</h3>
            <p className="text-blue-200">Unduhan Bulanan</p>
          </div>
        </div>
      </div>

      {/* Animated Background Elements */}
      <div className="absolute top-10 left-10 w-20 h-20 bg-white/5 rounded-full animate-pulse"></div>
      <div className="absolute top-32 right-20 w-16 h-16 bg-blue-300/10 rounded-full animate-bounce"></div>
      <div className="absolute bottom-20 left-20 w-12 h-12 bg-white/5 rounded-full animate-pulse"></div>
      <div className="absolute bottom-32 right-10 w-24 h-24 bg-blue-200/5 rounded-full animate-pulse"></div>
    </section>
  );
};

export default Hero;