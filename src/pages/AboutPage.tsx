import React from 'react';
import { Target, Eye, Users, Lightbulb, Shield, Award, Globe, Heart, Building2, Trophy, ArrowLeft, Mail, Phone, MapPin } from 'lucide-react';
import { Link } from 'react-router-dom';

const AboutPage = () => {
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

  const timeline = [
    {
      year: '2021',
      title: 'Peluncuran Portal',
      description: 'Portal Data Terbuka Kabupaten Gorontalo resmi diluncurkan dengan 25 dataset awal'
    },
    {
      year: '2022',
      title: 'Ekspansi Dataset',
      description: 'Penambahan 75 dataset baru dan integrasi dengan 10 dinas/instansi pemerintah'
    },
    {
      year: '2023',
      title: 'Penghargaan Nasional',
      description: 'Meraih Juara 1 Portal Data Terbuka Terbaik tingkat nasional dari Kementerian KOMINFO'
    },
    {
      year: '2024',
      title: 'Inovasi API',
      description: 'Peluncuran API publik dan dashboard analytics untuk developer dan peneliti'
    }
  ];

  const team = [
    {
      name: 'Dr. Ahmad Wijaya, S.Kom., M.T.',
      position: 'Kepala Dinas Komunikasi dan Informatika',
      department: 'Diskominfo Kabupaten Gorontalo',
      image: 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=300&h=300&fit=crop'
    },
    {
      name: 'Siti Nurhaliza, S.T., M.Kom.',
      position: 'Koordinator Portal Data Terbuka',
      department: 'Diskominfo Kabupaten Gorontalo',
      image: 'https://images.pexels.com/photos/3763188/pexels-photo-3763188.jpeg?auto=compress&cs=tinysrgb&w=300&h=300&fit=crop'
    },
    {
      name: 'Budi Santoso, S.Kom.',
      position: 'Data Analyst & Developer',
      department: 'Diskominfo Kabupaten Gorontalo',
      image: 'https://images.pexels.com/photos/2182970/pexels-photo-2182970.jpeg?auto=compress&cs=tinysrgb&w=300&h=300&fit=crop'
    }
  ];

  return (
    <div className="min-h-screen bg-white">
      {/* Header */}
      <header className="bg-white shadow-sm border-b sticky top-0 z-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="flex items-center">
              <Link to="/" className="flex items-center text-gray-600 hover:text-red-700 transition-colors mr-6">
                <ArrowLeft className="h-5 w-5 mr-2" />
                Kembali ke Beranda
              </Link>
              <div className="flex items-center">
                <Globe className="h-8 w-8 text-red-700" />
                <div className="ml-3">
                  <h1 className="text-xl font-bold text-gray-900">Portal Data Terbuka</h1>
                  <p className="text-sm text-gray-600">Kabupaten Gorontalo</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>

      {/* Hero Section */}
      <section className="py-20 bg-gradient-to-br from-red-700 via-red-800 to-red-900 text-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center">
            <div className="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur-sm rounded-3xl mb-8 border border-white/20">
              <Heart className="h-10 w-10 text-white" />
            </div>
            <h1 className="text-5xl md:text-6xl font-bold mb-6">
              Tentang Kami
            </h1>
            <p className="text-xl md:text-2xl text-red-100 max-w-4xl mx-auto leading-relaxed">
              Membangun masa depan yang lebih transparan dan inovatif melalui 
              keterbukaan data publik Kabupaten Gorontalo
            </p>
          </div>
        </div>
      </section>

      {/* Vision & Mission */}
      <section className="py-20 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div className="bg-gradient-to-br from-red-700 to-red-800 rounded-3xl p-8 md:p-12 text-white">
              <div className="flex items-center mb-6">
                <Eye className="h-12 w-12 text-red-200 mr-4" />
                <h2 className="text-3xl font-bold">Visi Kami</h2>
              </div>
              <p className="text-xl text-red-100 leading-relaxed mb-8">
                "Menjadi portal data terbuka terdepan di Indonesia yang mendukung 
                transparansi, inovasi, dan pembangunan berkelanjutan untuk 
                kesejahteraan masyarakat Kabupaten Gorontalo"
              </p>
              <div className="grid grid-cols-2 gap-4">
                {achievements.map((achievement, index) => {
                  const IconComponent = achievement.icon;
                  return (
                    <div key={index} className="text-center p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                      <IconComponent className="h-6 w-6 text-amber-300 mx-auto mb-2" />
                      <div className="text-2xl font-bold mb-1 text-amber-200">{achievement.value}</div>
                      <div className="text-xs text-red-200">{achievement.label}</div>
                    </div>
                  );
                })}
              </div>
            </div>

            <div className="bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-gray-100">
              <div className="flex items-center mb-6">
                <Target className="h-12 w-12 text-blue-700 mr-4" />
                <h2 className="text-3xl font-bold text-gray-900">Misi Kami</h2>
              </div>
              <div className="space-y-4">
                <div className="flex items-start">
                  <div className="w-2 h-2 bg-red-600 rounded-full mt-2 mr-4 flex-shrink-0"></div>
                  <p className="text-gray-700">Menyediakan akses mudah dan gratis ke data publik berkualitas tinggi</p>
                </div>
                <div className="flex items-start">
                  <div className="w-2 h-2 bg-blue-700 rounded-full mt-2 mr-4 flex-shrink-0"></div>
                  <p className="text-gray-700">Mendorong transparansi dan akuntabilitas pemerintah daerah</p>
                </div>
                <div className="flex items-start">
                  <div className="w-2 h-2 bg-amber-600 rounded-full mt-2 mr-4 flex-shrink-0"></div>
                  <p className="text-gray-700">Memfasilitasi inovasi dan pengembangan aplikasi berbasis data</p>
                </div>
                <div className="flex items-start">
                  <div className="w-2 h-2 bg-slate-600 rounded-full mt-2 mr-4 flex-shrink-0"></div>
                  <p className="text-gray-700">Meningkatkan partisipasi masyarakat dalam pembangunan daerah</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Why Open Data Matters */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-6">
              Mengapa Data Terbuka Penting?
            </h2>
            <p className="text-xl text-gray-600 max-w-3xl mx-auto">
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
                      <h3 className="text-xl font-bold text-gray-900 mb-3">
                        {feature.title}
                      </h3>
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
      </section>

      {/* Timeline */}
      <section className="py-20 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-6">
              Perjalanan Kami
            </h2>
            <p className="text-xl text-gray-600 max-w-2xl mx-auto">
              Melihat kembali pencapaian dan milestone penting dalam pengembangan Portal Data Terbuka
            </p>
          </div>

          <div className="relative">
            <div className="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-red-200"></div>
            <div className="space-y-12">
              {timeline.map((item, index) => (
                <div key={index} className={`flex items-center ${index % 2 === 0 ? 'flex-row' : 'flex-row-reverse'}`}>
                  <div className={`w-1/2 ${index % 2 === 0 ? 'pr-8 text-right' : 'pl-8 text-left'}`}>
                    <div className="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                      <div className="text-2xl font-bold text-red-700 mb-2">{item.year}</div>
                      <h3 className="text-xl font-bold text-gray-900 mb-3">{item.title}</h3>
                      <p className="text-gray-600">{item.description}</p>
                    </div>
                  </div>
                  <div className="relative z-10 w-4 h-4 bg-red-700 rounded-full border-4 border-white shadow-lg"></div>
                  <div className="w-1/2"></div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Our Commitments */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-6">
              Komitmen Kami
            </h2>
            <p className="text-xl text-gray-600 max-w-2xl mx-auto">
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
                  <h3 className="text-xl font-bold text-gray-900 mb-3">
                    {commitment.title}
                  </h3>
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
      </section>

      {/* Team */}
      <section className="py-20 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-6">
              Tim Kami
            </h2>
            <p className="text-xl text-gray-600 max-w-2xl mx-auto">
              Profesional berpengalaman yang berdedikasi untuk menghadirkan portal data terbuka terbaik
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {team.map((member, index) => (
              <div key={index} className="bg-white rounded-2xl p-8 shadow-lg text-center group hover:shadow-xl transition-all border border-gray-100">
                <div className="w-24 h-24 mx-auto mb-6 rounded-full overflow-hidden group-hover:scale-110 transition-transform ring-4 ring-red-100">
                  <img 
                    src={member.image} 
                    alt={member.name}
                    className="w-full h-full object-cover"
                  />
                </div>
                <h3 className="text-xl font-bold text-gray-900 mb-2">{member.name}</h3>
                <p className="text-red-700 font-medium mb-2">{member.position}</p>
                <p className="text-gray-600 text-sm">{member.department}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Contact Section */}
      <section className="py-20 bg-gradient-to-br from-red-700 via-red-800 to-red-900 text-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-4xl font-bold mb-6">
              Hubungi Kami
            </h2>
            <p className="text-xl text-red-100 max-w-2xl mx-auto">
              Ada pertanyaan atau saran? Tim kami siap membantu Anda
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="text-center p-6 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
              <MapPin className="h-12 w-12 text-red-200 mx-auto mb-4" />
              <h3 className="text-xl font-bold mb-2">Alamat</h3>
              <p className="text-red-100">
                Jl. 23 Januari No. 43<br />
                Limboto, Gorontalo 96212
              </p>
            </div>
            <div className="text-center p-6 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
              <Phone className="h-12 w-12 text-red-200 mx-auto mb-4" />
              <h3 className="text-xl font-bold mb-2">Telepon</h3>
              <p className="text-red-100">
                (0435) 881234<br />
                Senin - Jumat, 08:00 - 16:00
              </p>
            </div>
            <div className="text-center p-6 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
              <Mail className="h-12 w-12 text-red-200 mx-auto mb-4" />
              <h3 className="text-xl font-bold mb-2">Email</h3>
              <p className="text-red-100">
                opendata@gorontalokab.go.id<br />
                support@gorontalokab.go.id
              </p>
            </div>
          </div>

          <div className="text-center mt-12">
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Link 
                to="/"
                className="inline-flex items-center px-6 py-3 bg-white text-red-700 font-semibold rounded-lg hover:bg-red-50 transition-colors shadow-lg"
              >
                <Globe className="h-5 w-5 mr-2" />
                Kembali ke Portal
              </Link>
              <button className="inline-flex items-center px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-500 transition-colors shadow-lg border border-red-500">
                <Users className="h-5 w-5 mr-2" />
                Bergabung Komunitas
              </button>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default AboutPage;