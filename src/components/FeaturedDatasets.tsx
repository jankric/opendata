import React from 'react';
import { Download, Eye, Calendar, FileText, BarChart3 } from 'lucide-react';

const datasets = [
  {
    title: 'Data Penduduk Kabupaten Gorontalo 2024',
    description: 'Dataset lengkap mengenai jumlah penduduk, pertumbuhan demografis, dan distribusi usia di seluruh kecamatan.',
    category: 'Kependudukan',
    lastUpdate: '15 Jan 2024',
    downloads: 1243,
    views: 5621,
    format: 'CSV, JSON',
    size: '2.4 MB'
  },
  {
    title: 'PDRB dan Indikator Ekonomi 2023',
    description: 'Produk Domestik Regional Bruto, tingkat inflasi, dan indikator ekonomi utama Kabupaten Gorontalo.',
    category: 'Ekonomi',
    lastUpdate: '10 Jan 2024',
    downloads: 892,
    views: 3456,
    format: 'Excel, PDF',
    size: '1.8 MB'
  },
  {
    title: 'Fasilitas Kesehatan dan Tenaga Medis',
    description: 'Data komprehensif rumah sakit, puskesmas, dokter, dan tenaga kesehatan di Kabupaten Gorontalo.',
    category: 'Kesehatan',
    lastUpdate: '08 Jan 2024',
    downloads: 567,
    views: 2134,
    format: 'CSV, XML',
    size: '3.1 MB'
  },
  {
    title: 'Statistik Pendidikan Dasar dan Menengah',
    description: 'Jumlah sekolah, siswa, guru, dan rasio kelulusan di tingkat SD, SMP, dan SMA sederajat.',
    category: 'Pendidikan',
    lastUpdate: '05 Jan 2024',
    downloads: 721,
    views: 4298,
    format: 'CSV, JSON',
    size: '1.9 MB'
  }
];

const FeaturedDatasets = () => {
  return (
    <section id="dataset" className="py-16 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Dataset Unggulan
          </h2>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            Dataset terpopuler dan paling sering diunduh oleh masyarakat, 
            peneliti, dan developer aplikasi
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
          {datasets.map((dataset, index) => (
            <div 
              key={index}
              className="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300 group"
            >
              <div className="flex items-start justify-between mb-4">
                <div className="flex-1">
                  <div className="flex items-center mb-2">
                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                      {dataset.category}
                    </span>
                  </div>
                  <h3 className="text-xl font-semibold text-gray-900 mb-3 group-hover:text-blue-700 transition-colors">
                    {dataset.title}
                  </h3>
                  <p className="text-gray-600 leading-relaxed">
                    {dataset.description}
                  </p>
                </div>
                <BarChart3 className="h-8 w-8 text-gray-400 ml-4 flex-shrink-0" />
              </div>

              <div className="grid grid-cols-2 gap-4 mb-6 text-sm text-gray-500">
                <div className="flex items-center">
                  <Calendar className="h-4 w-4 mr-2" />
                  <span>{dataset.lastUpdate}</span>
                </div>
                <div className="flex items-center">
                  <FileText className="h-4 w-4 mr-2" />
                  <span>{dataset.format}</span>
                </div>
                <div className="flex items-center">
                  <Download className="h-4 w-4 mr-2" />
                  <span>{dataset.downloads.toLocaleString()} unduhan</span>
                </div>
                <div className="flex items-center">
                  <Eye className="h-4 w-4 mr-2" />
                  <span>{dataset.views.toLocaleString()} views</span>
                </div>
              </div>

              <div className="flex items-center justify-between pt-4 border-t border-gray-100">
                <span className="text-sm text-gray-500 font-medium">
                  Ukuran: {dataset.size}
                </span>
                <div className="flex space-x-3">
                  <button className="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    <Eye className="h-4 w-4 mr-1" />
                    Lihat
                  </button>
                  <button className="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    <Download className="h-4 w-4 mr-1" />
                    Unduh
                  </button>
                </div>
              </div>
            </div>
          ))}
        </div>

        <div className="text-center mt-12">
          <button className="inline-flex items-center px-6 py-3 text-base font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
            Lihat Semua Dataset
            <span className="ml-2">→</span>
          </button>
        </div>
      </div>
    </section>
  );
};

export default FeaturedDatasets;