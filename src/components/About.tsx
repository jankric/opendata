import React from 'react';
import { Target, Eye, Users, Lightbulb, Shield, Award, Globe, Heart, Building2, Trophy } from 'lucide-react';

const About = () => {
  const features = [
    {
      icon: Users,
      title: 'Transparansi Pemerintahan',
      description: 'Memberikan akses terbuka kepada masyarakat untuk mengetahui kinerja dan kebijakan pemerintah daerah secara real-time.',
      color: 'bg-red-50 border-red-200 text-red-700'
    },
    {
      icon: Lightbulb,
      title: 'Mendorong Inovasi',
      description: 'Data terbuka memungkinkan developer, peneliti, dan startup untuk menciptakan aplikasi dan solusi inovatif bagi masyarakat.',
      color: 'bg-blue-50 border-blue-200 text-blue-700'
    },
    {
      icon: Target,
      title: 'Pengambilan Keputusan Berbasis Data',
      description: 'Mendukung penelitian akademis, bisnis, dan organisasi dalam membuat keputusan yang lebih tepat dan akurat.',
      color: 'bg-amber-50 border-amber-200 text-amber-700'
    },
    {
      icon: Shield,
      title: 'Keamanan Data Terjamin',
      description: 'Sistem keamanan berlapis dengan enkripsi tingkat enterprise untuk melindungi integritas dan privasi data.',
      color: 'bg-slate-50 border-slate-200 text-slate-700'
    }
  ];

  const commitments = [
    {
      icon: Globe,
      title: 'Aksesibilitas Universal',
      description: 'Memastikan data dapat diakses oleh semua kalangan dengan mudah, gratis, dan tanpa diskriminasi',
      stats: '24/7 Akses'
    },
    {
      icon: Target,
      title: 'Kualitas Data Premium',
      description: 'Menjamin akurasi, kelengkapan, dan pembaruan berkala dari semua dataset dengan standar internasional',
      stats: '99.9% Akurasi'
    },
    {
      icon: Lightbulb,
      title: 'Inovasi Berkelanjutan',
      description: 'Terus mengembangkan fitur dan layanan untuk pengalaman pengguna yang lebih baik dan modern',
      stats: 'Update Mingguan'
    }
  ];

  const achievements = [
    { label: 'Tahun Peluncuran', value: '2021', icon: Award },
    { label: 'Dinas Terlibat', value: '15+', icon: Building2 },
    { label: 'Penghargaan', value: '8', icon: Trophy },
    { label: 'Uptime', value: '99.8%', icon: Shield }
  ];

  return (
    <section id="tentang" className="py-20 bg-gradient-to-br from-gray-50 via-white to-red-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="text-center mb-16">
          <div className="inline-flex items-center justify-center w-16 h-16 bg-red-100 text-red-700 rounded-2xl mb-6">
            <Heart className="h-8 w-8" />
          </div>
          <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            Tentang Portal Data Terbuka
          </h2>
          <p className="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
            Portal Data Terbuka Kabupaten Gorontalo merupakan inisiatif revolusioner untuk meningkatkan 
            transparansi, akuntabilitas, dan mendorong inovasi melalui keterbukaan informasi publik yang berkualitas tinggi
          </p>
        </div>

        {/* Vision Statement */}
        <div className="bg-gradient-to-r from-red-700 to-red-800 rounded-3xl p-8 md:p-12 mb-16 text-white">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div>
              <div className="flex items-center mb-6">
                <Eye className="h-12 w-12 text-red-200 mr-4" />
                <h3 className="text-3xl font-bold">Visi Kami</h3>
              </div>
              <p className="text-xl text-red-100 leading-relaxed mb-6">
                "Menjadi portal data terbuka terdepan di Indonesia yang mendukung 
                transparansi, inovasi, dan pembangunan berkelanjutan untuk 
                kesejahteraan masyarakat Kabupaten Gorontalo"
              </p>
              <div className="flex items-center text-red-200">
                <Globe className="h-5 w-5 mr-2" />
                <span className="font-medium">Menuju Smart Government 2030</span>
              </div>
            </div>
            
            <div className="grid grid-cols-2 gap-4">
              {achievements.map((achievement, index) => {
                const IconComponent = achievement.icon;
                return (
                  <div key={index} className="text-center p-6 bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20">
                    <IconComponent className="h-8 w-8 text-amber-300 mx-auto mb-3" />
                    <div className="text-3xl font-bold mb-2 text-amber-200">{achievement.value}</div>
                    <div className="text-sm text-red-200">{achievement.label}</div>
                  </div>
                );
              })}
            </div>
          </div>
        </div>

        {/* Why Open Data Matters */}
        <div className="mb-16">
          <div className="text-center mb-12">
            <h3 className="text-3xl font-bold text-gray-900 mb-4">
              Mengapa Data Terbuka Penting?
            </h3>
            <p className="text-lg text-gray-600 max-w-3xl mx-auto">
              Data terbuka bukan hanya tentang transparansi, tetapi juga tentang memberdayakan masyarakat 
              dan menciptakan ekosistem inovasi yang berkelanjutan
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            {features.map((feature, index) => {
              const IconComponent = feature.icon;
              return (
                <div 
                  key={index} 
                  className={`p-8 rounded-2xl border-2 ${feature.color} hover:shadow-lg transition-all duration-300 group`}
                >
                  <div className="flex items-start">
                    <div className="flex-shrink-0 w-14 h-14 bg-white rounded-xl flex items-center justify-center mr-6 group-hover:scale-110 transition-transform shadow-sm">
                      <IconComponent className="h-7 w-7" />
                    </div>
                    <div>
                      <h4 className="text-xl font-bold text-gray-900 mb-3">
                        {feature.title}
                      </h4>
                      <p className="text-gray-700 leading-relaxed">
                        {feature.description}
                      </p>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        </div>

        {/* Our Commitments */}
        <div className="bg-white rounded-3xl shadow-xl p-8 md:p-12 border border-gray-100">
          <div className="text-center mb-12">
            <h3 className="text-3xl font-bold text-gray-900 mb-4">
              Komitmen Kami
            </h3>
            <p className="text-lg text-gray-600 max-w-2xl mx-auto">
              Dedikasi penuh untuk memberikan layanan data terbuka terbaik dengan standar internasional
            </p>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {commitments.map((commitment, index) => {
              const IconComponent = commitment.icon;
              const gradients = [
                'from-red-600 to-red-700',
                'from-blue-700 to-blue-800',
                'from-amber-600 to-amber-700'
              ];
              return (
                <div key={index} className="text-center group">
                  <div className={`w-20 h-20 bg-gradient-to-br ${gradients[index]} rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform shadow-lg`}>
                    <IconComponent className="h-10 w-10 text-white" />
                  </div>
                  <h4 className="text-xl font-bold text-gray-900 mb-3">
                    {commitment.title}
                  </h4>
                  <p className="text-gray-600 leading-relaxed mb-4">
                    {commitment.description}
                  </p>
                  <div className="inline-flex items-center px-4 py-2 bg-red-50 text-red-700 rounded-full text-sm font-semibold border border-red-200">
                    {commitment.stats}
                  </div>
                </div>
              );
            })}
          </div>
        </div>

        {/* Call to Action */}
        <div className="text-center mt-16">
          <div className="bg-gradient-to-r from-red-700 to-red-800 rounded-2xl p-8 text-white">
            <h3 className="text-2xl font-bold mb-4">
              Bergabunglah dalam Revolusi Data Terbuka
            </h3>
            <p className="text-red-100 mb-6 max-w-2xl mx-auto">
              Manfaatkan kekuatan data untuk menciptakan solusi inovatif dan berkontribusi 
              pada pembangunan Kabupaten Gorontalo yang lebih baik
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <button className="inline-flex items-center px-6 py-3 bg-white text-red-700 font-semibold rounded-lg hover:bg-red-50 transition-colors shadow-lg">
                <Globe className="h-5 w-5 mr-2" />
                Jelajahi Dataset
              </button>
              <button className="inline-flex items-center px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-500 transition-colors shadow-lg border border-red-500">
                <Users className="h-5 w-5 mr-2" />
                Bergabung Komunitas
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default About;