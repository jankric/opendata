import { apiClient, ApiResponse } from '../client';
import { API_CONFIG } from '../config';
import { Dataset } from './datasetService';
import { Category } from './categoryService';

export interface PublicStats {
  totalDatasets: number;
  totalDownloads: number;
  totalCategories: number;
  totalOrganizations: number;
  lastUpdate: string;
}

export interface PublicDatasetSearchParams {
  query?: string;
  category?: string;
  format?: string;
  page?: number;
  limit?: number;
  sortBy?: 'title' | 'downloads' | 'views' | 'createdAt';
  sortOrder?: 'asc' | 'desc';
}

// Mock stats data
const MOCK_STATS: PublicStats = {
  totalDatasets: 154,
  totalDownloads: 267000,
  totalCategories: 8,
  totalOrganizations: 15,
  lastUpdate: '2024-01-15T08:00:00Z'
};

// Simulate network delay
const delay = (ms: number) => new Promise(resolve => setTimeout(resolve, ms));

export class PublicService {
  private useMockService = process.env.NODE_ENV === 'development';

  async getPublicDatasets(params?: PublicDatasetSearchParams): Promise<ApiResponse<Dataset[]>> {
    try {
      if (this.useMockService) {
        // Use the existing dataset service for mock data
        const { datasetService } = await import('./datasetService');
        return await datasetService.getDatasets(params);
      }

      return await apiClient.get<Dataset[]>(
        API_CONFIG.ENDPOINTS.DATASETS.LIST,
        params
      );
    } catch (error: any) {
      console.error('Get public datasets error:', error);
      
      // Fallback to mock service
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        const { datasetService } = await import('./datasetService');
        return await datasetService.getDatasets(params);
      }
      
      throw error;
    }
  }

  async getPublicCategories(): Promise<ApiResponse<Category[]>> {
    try {
      if (this.useMockService) {
        // Use the existing category service for mock data
        const { categoryService } = await import('./categoryService');
        return await categoryService.getCategories();
      }

      return await apiClient.get<Category[]>(
        API_CONFIG.ENDPOINTS.CATEGORIES.LIST
      );
    } catch (error: any) {
      console.error('Get public categories error:', error);
      
      // Fallback to mock service
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        const { categoryService } = await import('./categoryService');
        return await categoryService.getCategories();
      }
      
      throw error;
    }
  }

  async getPublicStats(): Promise<ApiResponse<PublicStats>> {
    try {
      if (this.useMockService) {
        await delay(200);
        return {
          success: true,
          data: MOCK_STATS,
          message: 'Public stats retrieved successfully (mock)'
        };
      }

      return await apiClient.get<PublicStats>(
        API_CONFIG.ENDPOINTS.STATS
      );
    } catch (error: any) {
      console.error('Get public stats error:', error);
      
      // Fallback to mock service
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        await delay(200);
        return {
          success: true,
          data: MOCK_STATS,
          message: 'Public stats retrieved successfully (mock fallback)'
        };
      }
      
      throw error;
    }
  }

  async downloadPublicDataset(id: string): Promise<void> {
    try {
      if (this.useMockService) {
        // Use the existing dataset service for mock download
        const { datasetService } = await import('./datasetService');
        return await datasetService.downloadDataset(id);
      }

      const response = await fetch(
        `${API_CONFIG.BASE_URL}${API_CONFIG.ENDPOINTS.RESOURCES.DOWNLOAD(id)}`
      );

      if (!response.ok) {
        throw new Error('Download failed');
      }

      const blob = await response.blob();
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = response.headers.get('Content-Disposition')?.split('filename=')[1] || 'dataset.csv';
      document.body.appendChild(a);
      a.click();
      window.URL.revokeObjectURL(url);
      document.body.removeChild(a);
    } catch (error: any) {
      console.error('Download public dataset error:', error);
      
      // Fallback to mock service
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        const { datasetService } = await import('./datasetService');
        return await datasetService.downloadDataset(id);
      }
      
      throw error;
    }
  }
}

export const publicService = new PublicService();