import { ApiResponse } from '../client';
import { Dataset, CreateDatasetRequest, UpdateDatasetRequest, DatasetSearchParams } from './datasetService';

// Mock dataset data
const MOCK_DATASETS: Dataset[] = [
  {
    id: '1',
    title: 'Data Penduduk Kabupaten Gorontalo 2024',
    description: 'Dataset lengkap mengenai jumlah penduduk, pertumbuhan demografis, dan distribusi usia di seluruh kecamatan.',
    category: 'Kependudukan',
    categoryId: '1',
    format: 'CSV, JSON',
    size: 2516582, // 2.4 MB in bytes
    downloads: 1243,
    views: 5621,
    status: 'published',
    tags: ['penduduk', 'demografi', 'statistik'],
    fileUrl: 'https://example.com/datasets/penduduk-2024.csv',
    fileName: 'penduduk-gorontalo-2024.csv',
    createdBy: 'Ahmad Wijaya',
    createdAt: '2024-01-15T08:00:00Z',
    updatedAt: '2024-01-15T08:00:00Z',
    lastUpdate: '2024-01-15T08:00:00Z'
  },
  {
    id: '2',
    title: 'PDRB dan Indikator Ekonomi 2023',
    description: 'Produk Domestik Regional Bruto, tingkat inflasi, dan indikator ekonomi utama Kabupaten Gorontalo.',
    category: 'Ekonomi',
    categoryId: '2',
    format: 'Excel, PDF',
    size: 1887437, // 1.8 MB in bytes
    downloads: 892,
    views: 3456,
    status: 'published',
    tags: ['ekonomi', 'pdrb', 'inflasi'],
    fileUrl: 'https://example.com/datasets/pdrb-2023.xlsx',
    fileName: 'pdrb-gorontalo-2023.xlsx',
    createdBy: 'Siti Nurhaliza',
    createdAt: '2024-01-10T09:30:00Z',
    updatedAt: '2024-01-10T09:30:00Z',
    lastUpdate: '2024-01-10T09:30:00Z'
  },
  {
    id: '3',
    title: 'Fasilitas Kesehatan dan Tenaga Medis',
    description: 'Data komprehensif rumah sakit, puskesmas, dokter, dan tenaga kesehatan di Kabupaten Gorontalo.',
    category: 'Kesehatan',
    categoryId: '3',
    format: 'CSV, XML',
    size: 3251200, // 3.1 MB in bytes
    downloads: 567,
    views: 2134,
    status: 'published',
    tags: ['kesehatan', 'rumah sakit', 'dokter'],
    fileUrl: 'https://example.com/datasets/kesehatan-2024.csv',
    fileName: 'fasilitas-kesehatan-2024.csv',
    createdBy: 'Budi Santoso',
    createdAt: '2024-01-08T14:15:00Z',
    updatedAt: '2024-01-08T14:15:00Z',
    lastUpdate: '2024-01-08T14:15:00Z'
  },
  {
    id: '4',
    title: 'Statistik Pendidikan Dasar dan Menengah',
    description: 'Jumlah sekolah, siswa, guru, dan rasio kelulusan di tingkat SD, SMP, dan SMA sederajat.',
    category: 'Pendidikan',
    categoryId: '4',
    format: 'CSV, JSON',
    size: 1992294, // 1.9 MB in bytes
    downloads: 721,
    views: 4298,
    status: 'review',
    tags: ['pendidikan', 'sekolah', 'siswa'],
    fileUrl: 'https://example.com/datasets/pendidikan-2024.csv',
    fileName: 'statistik-pendidikan-2024.csv',
    createdBy: 'Ahmad Wijaya',
    createdAt: '2024-01-05T11:20:00Z',
    updatedAt: '2024-01-05T11:20:00Z',
    lastUpdate: '2024-01-05T11:20:00Z'
  },
  {
    id: '5',
    title: 'Data Infrastruktur Jalan dan Jembatan',
    description: 'Kondisi jalan, jembatan, dan infrastruktur transportasi di Kabupaten Gorontalo.',
    category: 'Infrastruktur',
    categoryId: '5',
    format: 'CSV, GeoJSON',
    size: 4194304, // 4.0 MB in bytes
    downloads: 445,
    views: 1876,
    status: 'draft',
    tags: ['infrastruktur', 'jalan', 'transportasi'],
    fileUrl: 'https://example.com/datasets/infrastruktur-2024.csv',
    fileName: 'infrastruktur-jalan-2024.csv',
    createdBy: 'Siti Nurhaliza',
    createdAt: '2024-01-03T16:45:00Z',
    updatedAt: '2024-01-03T16:45:00Z',
    lastUpdate: '2024-01-03T16:45:00Z'
  }
];

// Simulate network delay
const delay = (ms: number) => new Promise(resolve => setTimeout(resolve, ms));

export class MockDatasetService {
  private datasets: Dataset[] = [...MOCK_DATASETS];

  async getDatasets(params?: DatasetSearchParams): Promise<ApiResponse<Dataset[]>> {
    await delay(500); // Simulate network delay

    let filteredDatasets = [...this.datasets];

    // Apply filters
    if (params?.query) {
      const query = params.query.toLowerCase();
      filteredDatasets = filteredDatasets.filter(dataset =>
        dataset.title.toLowerCase().includes(query) ||
        dataset.description.toLowerCase().includes(query) ||
        dataset.tags.some(tag => tag.toLowerCase().includes(query))
      );
    }

    if (params?.category && params.category !== 'all') {
      filteredDatasets = filteredDatasets.filter(dataset =>
        dataset.categoryId === params.category
      );
    }

    if (params?.status && params.status !== 'all') {
      filteredDatasets = filteredDatasets.filter(dataset =>
        dataset.status === params.status
      );
    }

    if (params?.format) {
      filteredDatasets = filteredDatasets.filter(dataset =>
        dataset.format.toLowerCase().includes(params.format!.toLowerCase())
      );
    }

    // Apply sorting
    if (params?.sortBy) {
      filteredDatasets.sort((a, b) => {
        let aValue: any, bValue: any;
        
        switch (params.sortBy) {
          case 'title':
            aValue = a.title;
            bValue = b.title;
            break;
          case 'downloads':
            aValue = a.downloads;
            bValue = b.downloads;
            break;
          case 'views':
            aValue = a.views;
            bValue = b.views;
            break;
          case 'createdAt':
            aValue = new Date(a.createdAt);
            bValue = new Date(b.createdAt);
            break;
          default:
            return 0;
        }

        if (params.sortOrder === 'desc') {
          return aValue > bValue ? -1 : aValue < bValue ? 1 : 0;
        } else {
          return aValue < bValue ? -1 : aValue > bValue ? 1 : 0;
        }
      });
    }

    // Apply pagination
    const page = params?.page || 1;
    const limit = params?.limit || 10;
    const startIndex = (page - 1) * limit;
    const endIndex = startIndex + limit;
    const paginatedDatasets = filteredDatasets.slice(startIndex, endIndex);

    return {
      success: true,
      data: paginatedDatasets,
      message: 'Datasets retrieved successfully (mock)',
      pagination: {
        page,
        limit,
        total: filteredDatasets.length,
        totalPages: Math.ceil(filteredDatasets.length / limit)
      }
    };
  }

  async getDataset(id: string): Promise<ApiResponse<Dataset>> {
    await delay(300);

    const dataset = this.datasets.find(d => d.id === id);
    if (!dataset) {
      throw new Error('Dataset tidak ditemukan');
    }

    return {
      success: true,
      data: dataset,
      message: 'Dataset retrieved successfully (mock)'
    };
  }

  async createDataset(data: CreateDatasetRequest): Promise<ApiResponse<Dataset>> {
    await delay(800);

    const newDataset: Dataset = {
      id: (this.datasets.length + 1).toString(),
      title: data.title,
      description: data.description,
      category: 'Unknown', // Will be resolved by category lookup
      categoryId: data.categoryId,
      format: 'CSV', // Default format
      size: 1024000, // 1MB default
      downloads: 0,
      views: 0,
      status: data.status || 'draft',
      tags: data.tags || [],
      fileUrl: '',
      fileName: '',
      createdBy: 'Current User',
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString(),
      lastUpdate: new Date().toISOString()
    };

    this.datasets.push(newDataset);

    return {
      success: true,
      data: newDataset,
      message: 'Dataset created successfully (mock)'
    };
  }

  async updateDataset(data: UpdateDatasetRequest): Promise<ApiResponse<Dataset>> {
    await delay(600);

    const index = this.datasets.findIndex(d => d.id === data.id);
    if (index === -1) {
      throw new Error('Dataset tidak ditemukan');
    }

    const updatedDataset = {
      ...this.datasets[index],
      ...data,
      updatedAt: new Date().toISOString(),
      lastUpdate: new Date().toISOString()
    };

    this.datasets[index] = updatedDataset;

    return {
      success: true,
      data: updatedDataset,
      message: 'Dataset updated successfully (mock)'
    };
  }

  async deleteDataset(id: string): Promise<ApiResponse> {
    await delay(400);

    const index = this.datasets.findIndex(d => d.id === id);
    if (index === -1) {
      throw new Error('Dataset tidak ditemukan');
    }

    this.datasets.splice(index, 1);

    return {
      success: true,
      message: 'Dataset deleted successfully (mock)'
    };
  }

  async uploadDatasetFile(datasetId: string, file: File): Promise<ApiResponse<{ fileUrl: string; fileName: string; size: number }>> {
    await delay(1000); // Simulate file upload time

    const dataset = this.datasets.find(d => d.id === datasetId);
    if (!dataset) {
      throw new Error('Dataset tidak ditemukan');
    }

    // Simulate file upload
    const fileInfo = {
      fileUrl: `https://example.com/datasets/${file.name}`,
      fileName: file.name,
      size: file.size
    };

    // Update dataset with file info
    dataset.fileUrl = fileInfo.fileUrl;
    dataset.fileName = fileInfo.fileName;
    dataset.size = fileInfo.size;
    dataset.format = file.name.split('.').pop()?.toUpperCase() || 'Unknown';

    return {
      success: true,
      data: fileInfo,
      message: 'File uploaded successfully (mock)'
    };
  }

  async downloadDataset(id: string): Promise<void> {
    await delay(200);

    const dataset = this.datasets.find(d => d.id === id);
    if (!dataset) {
      throw new Error('Dataset tidak ditemukan');
    }

    // Simulate download by creating a mock file
    const content = `Mock dataset content for: ${dataset.title}\nGenerated at: ${new Date().toISOString()}`;
    const blob = new Blob([content], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = dataset.fileName || `${dataset.title}.csv`;
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);

    // Increment download count
    dataset.downloads += 1;
  }

  async searchDatasets(query: string, filters?: Omit<DatasetSearchParams, 'query'>): Promise<ApiResponse<Dataset[]>> {
    return this.getDatasets({ query, ...filters });
  }

  async getPopularDatasets(limit = 10): Promise<ApiResponse<Dataset[]>> {
    await delay(300);

    const popularDatasets = [...this.datasets]
      .sort((a, b) => b.downloads - a.downloads)
      .slice(0, limit);

    return {
      success: true,
      data: popularDatasets,
      message: 'Popular datasets retrieved successfully (mock)'
    };
  }

  async getRecentDatasets(limit = 10): Promise<ApiResponse<Dataset[]>> {
    await delay(300);

    const recentDatasets = [...this.datasets]
      .sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime())
      .slice(0, limit);

    return {
      success: true,
      data: recentDatasets,
      message: 'Recent datasets retrieved successfully (mock)'
    };
  }
}

export const mockDatasetService = new MockDatasetService();