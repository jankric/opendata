import React from 'react';
import { 
  Users, 
  Building2, 
  Heart, 
  GraduationCap, 
  Zap, 
  MapPin, 
  Briefcase, 
  TreePine,
  Shield,
  TrendingUp
} from 'lucide-react';

const categories = [
  {
    icon: Users,
    title: 'Kependudukan',
    description: 'Data demografis dan statistik penduduk',
    count: 25,
    color: 'bg-blue-100 text-blue-700'
  },
  {
    icon: TrendingUp,
    title: 'Ekonomi',
    description: 'PDRB, inflasi, dan indikator ekonomi',
    count: 18,
    color: 'bg-green-100 text-green-700'
  },
  {
    icon: Heart,
    title: 'Kesehatan',
    description: 'Fasilitas dan layanan kesehatan',
    count: 22,
    color: 'bg-red-100 text-red-700'
  },
  {
    icon: GraduationCap,
    title: 'Pendidikan',
    description: 'Sekolah, siswa, dan sarana pendidikan',
    count: 15,
    color: 'bg-purple-100 text-purple-700'
  },
  {
    icon: Building2,
    title: 'Infrastruktur',
    description: 'Jalan, jembatan, dan bangunan publik',
    count: 12,
    color: 'bg-orange-100 text-orange-700'
  },
  {
    icon: TreePine,
    title: 'Lingkungan',
    description: 'Kualitas udara, air, dan konservasi',
    count: 8,
    color: 'bg-emerald-100 text-emerald-700'
  },
  {
    icon: Briefcase,
    title: 'Ketenagakerjaan',
    description: 'Angkatan kerja dan lapangan kerja',
    count: 10,
    color: 'bg-indigo-100 text-indigo-700'
  },
  {
    icon: Shield,
    title: 'Keamanan',
    description: 'Ketertiban dan keamanan publik',
    count: 7,
    color: 'bg-slate-100 text-slate-700'
  }
];

const Categories = () => {
  return (
    <section id="kategori" className="py-16 bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Kategori Data
          </h2>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            Jelajahi berbagai kategori data publik yang tersedia untuk mendukung 
            penelitian, analisis, dan pengembangan aplikasi
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          {categories.map((category, index) => {
            const IconComponent = category.icon;
            return (
              <div 
                key={index}
                className="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer group border border-gray-100"
              >
                <div className={`inline-flex p-3 rounded-lg ${category.color} mb-4 group-hover:scale-110 transition-transform`}>
                  <IconComponent className="h-6 w-6" />
                </div>
                <h3 className="text-lg font-semibold text-gray-900 mb-2">
                  {category.title}
                </h3>
                <p className="text-gray-600 text-sm mb-3">
                  {category.description}
                </p>
                <div className="flex items-center justify-between">
                  <span className="text-sm text-gray-500">
                    {category.count} dataset
                  </span>
                  <span className="text-blue-600 text-sm font-medium group-hover:text-blue-700">
                    Lihat semua →
                  </span>
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
};

export default Categories;