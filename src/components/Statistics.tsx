import React from 'react';
import { TrendingUp, Download, Users, Database, Globe, Award } from 'lucide-react';

const stats = [
  {
    icon: Database,
    label: 'Total Dataset',
    value: '154',
    change: '+12',
    changeLabel: 'bulan ini'
  },
  {
    icon: Download,
    label: 'Total Unduhan',
    value: '267K',
    change: '+8.2%',
    changeLabel: 'dari bulan lalu'
  },
  {
    icon: Users,
    label: 'Pengguna Aktif',
    value: '5,234',
    change: '+15%',
    changeLabel: 'dari bulan lalu'
  },
  {
    icon: Globe,
    label: 'Kunjungan Halaman',
    value: '89K',
    change: '+22%',
    changeLabel: 'dari bulan lalu'
  }
];

const Statistics = () => {
  return (
    <section className="py-16 bg-gradient-to-br from-blue-50 to-indigo-100">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Statistik Portal
          </h2>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            Pencapaian dan perkembangan Portal Data Terbuka Kabupaten Gorontalo 
            dalam mendukung transparansi dan inovasi
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
          {stats.map((stat, index) => {
            const IconComponent = stat.icon;
            return (
              <div 
                key={index}
                className="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 text-center"
              >
                <div className="inline-flex items-center justify-center w-12 h-12 bg-blue-100 text-blue-600 rounded-lg mb-4">
                  <IconComponent className="h-6 w-6" />
                </div>
                <div className="text-3xl font-bold text-gray-900 mb-1">
                  {stat.value}
                </div>
                <div className="text-gray-600 font-medium mb-2">
                  {stat.label}
                </div>
                <div className="flex items-center justify-center text-sm">
                  <TrendingUp className="h-4 w-4 text-green-500 mr-1" />
                  <span className="text-green-600 font-medium mr-1">
                    {stat.change}
                  </span>
                  <span className="text-gray-500">
                    {stat.changeLabel}
                  </span>
                </div>
              </div>
            );
          })}
        </div>

        <div className="bg-white rounded-2xl p-8 shadow-sm">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div>
              <div className="flex items-center mb-4">
                <Award className="h-8 w-8 text-yellow-500 mr-3" />
                <h3 className="text-2xl font-bold text-gray-900">
                  Penghargaan & Pengakuan
                </h3>
              </div>
              <p className="text-gray-600 mb-6 leading-relaxed">
                Portal Data Terbuka Kabupaten Gorontalo telah meraih berbagai penghargaan 
                dalam bidang transparansi dan inovasi digital dari pemerintah pusat dan 
                organisasi internasional.
              </p>
              <div className="space-y-3">
                <div className="flex items-center">
                  <div className="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                  <span className="text-gray-700">Juara 1 Portal Data Terbuka Terbaik 2023</span>
                </div>
                <div className="flex items-center">
                  <div className="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                  <span className="text-gray-700">Sertifikat ISO 27001 untuk Keamanan Data</span>
                </div>
                <div className="flex items-center">
                  <div className="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                  <span className="text-gray-700">Penghargaan Transparansi Digital KOMINFO</span>
                </div>
              </div>
            </div>
            <div className="lg:text-right">
              <div className="inline-block bg-gradient-to-br from-blue-500 to-purple-600 text-white p-8 rounded-2xl">
                <div className="text-4xl font-bold mb-2">99.8%</div>
                <div className="text-blue-100 font-medium mb-4">Uptime Portal</div>
                <div className="text-sm text-blue-200">
                  Ketersediaan sistem 24/7 dengan dukungan teknis terdepan
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default Statistics;