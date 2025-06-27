import { apiClient, ApiResponse } from '../client';
import { API_CONFIG } from '../config';

export interface Category {
  id: string;
  name: string;
  description: string;
  slug: string;
  icon: string;
  color: string;
  datasetCount: number;
  isActive: boolean;
  createdAt: string;
  updatedAt: string;
}

export interface CreateCategoryRequest {
  name: string;
  description: string;
  icon: string;
  color: string;
  isActive?: boolean;
}

export interface UpdateCategoryRequest extends Partial<CreateCategoryRequest> {
  id: string;
}

// Mock categories data
const MOCK_CATEGORIES: Category[] = [
  {
    id: '1',
    name: 'Kependudukan',
    description: 'Data demografis dan statistik penduduk',
    slug: 'kependudukan',
    icon: 'Users',
    color: 'bg-blue-100 text-blue-700',
    datasetCount: 25,
    isActive: true,
    createdAt: '2023-06-15T00:00:00Z',
    updatedAt: '2024-01-15T00:00:00Z'
  },
  {
    id: '2',
    name: 'Ekonomi',
    description: 'PDRB, inflasi, dan indikator ekonomi',
    slug: 'ekonomi',
    icon: 'TrendingUp',
    color: 'bg-green-100 text-green-700',
    datasetCount: 18,
    isActive: true,
    createdAt: '2023-06-15T00:00:00Z',
    updatedAt: '2024-01-14T00:00:00Z'
  },
  {
    id: '3',
    name: 'Kesehatan',
    description: 'Fasilitas dan layanan kesehatan',
    slug: 'kesehatan',
    icon: 'Heart',
    color: 'bg-red-100 text-red-700',
    datasetCount: 22,
    isActive: true,
    createdAt: '2023-06-15T00:00:00Z',
    updatedAt: '2024-01-13T00:00:00Z'
  },
  {
    id: '4',
    name: 'Pendidikan',
    description: 'Sekolah, siswa, dan sarana pendidikan',
    slug: 'pendidikan',
    icon: 'GraduationCap',
    color: 'bg-purple-100 text-purple-700',
    datasetCount: 15,
    isActive: true,
    createdAt: '2023-06-15T00:00:00Z',
    updatedAt: '2024-01-12T00:00:00Z'
  },
  {
    id: '5',
    name: 'Infrastruktur',
    description: 'Jalan, jembatan, dan bangunan publik',
    slug: 'infrastruktur',
    icon: 'Building2',
    color: 'bg-orange-100 text-orange-700',
    datasetCount: 12,
    isActive: true,
    createdAt: '2023-06-15T00:00:00Z',
    updatedAt: '2024-01-11T00:00:00Z'
  }
];

// Simulate network delay
const delay = (ms: number) => new Promise(resolve => setTimeout(resolve, ms));

export class CategoryService {
  private useMockService = process.env.NODE_ENV === 'development';

  async getCategories(): Promise<ApiResponse<Category[]>> {
    try {
      // Use mock service in development or when backend is not available
      if (this.useMockService) {
        await delay(300);
        return {
          success: true,
          data: MOCK_CATEGORIES,
          message: 'Categories retrieved successfully (mock)'
        };
      }

      return await apiClient.get<Category[]>(
        API_CONFIG.ENDPOINTS.CATEGORIES.LIST
      );
    } catch (error: any) {
      console.error('Get categories error:', error);
      
      // Fallback to mock service if API call fails
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        await delay(300);
        return {
          success: true,
          data: MOCK_CATEGORIES,
          message: 'Categories retrieved successfully (mock fallback)'
        };
      }
      
      throw error;
    }
  }

  async getCategory(id: string): Promise<ApiResponse<Category>> {
    try {
      if (this.useMockService) {
        await delay(200);
        const category = MOCK_CATEGORIES.find(c => c.id === id);
        if (!category) {
          throw new Error('Category tidak ditemukan');
        }
        return {
          success: true,
          data: category,
          message: 'Category retrieved successfully (mock)'
        };
      }

      return await apiClient.get<Category>(
        API_CONFIG.ENDPOINTS.CATEGORIES.GET(id)
      );
    } catch (error: any) {
      console.error('Get category error:', error);
      
      // Fallback to mock service
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        await delay(200);
        const category = MOCK_CATEGORIES.find(c => c.id === id);
        if (!category) {
          throw new Error('Category tidak ditemukan');
        }
        return {
          success: true,
          data: category,
          message: 'Category retrieved successfully (mock fallback)'
        };
      }
      
      throw error;
    }
  }

  async createCategory(data: CreateCategoryRequest): Promise<ApiResponse<Category>> {
    try {
      if (this.useMockService) {
        await delay(500);
        throw new Error('Create category not available in mock mode');
      }

      return await apiClient.post<Category>(
        API_CONFIG.ENDPOINTS.CATEGORIES.CREATE,
        data
      );
    } catch (error) {
      console.error('Create category error:', error);
      throw error;
    }
  }

  async updateCategory(data: UpdateCategoryRequest): Promise<ApiResponse<Category>> {
    try {
      if (this.useMockService) {
        await delay(500);
        throw new Error('Update category not available in mock mode');
      }

      return await apiClient.put<Category>(
        API_CONFIG.ENDPOINTS.CATEGORIES.UPDATE(data.id),
        data
      );
    } catch (error) {
      console.error('Update category error:', error);
      throw error;
    }
  }

  async deleteCategory(id: string): Promise<ApiResponse> {
    try {
      if (this.useMockService) {
        await delay(400);
        throw new Error('Delete category not available in mock mode');
      }

      return await apiClient.delete(
        API_CONFIG.ENDPOINTS.CATEGORIES.DELETE(id)
      );
    } catch (error) {
      console.error('Delete category error:', error);
      throw error;
    }
  }
}

export const categoryService = new CategoryService();