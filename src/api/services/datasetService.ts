import { apiClient, ApiResponse } from '../client';
import { API_CONFIG } from '../config';
import { mockDatasetService } from './mockDatasetService';

export interface Dataset {
  id: string;
  title: string;
  description: string;
  category: string;
  categoryId: string;
  format: string;
  size: number;
  downloads: number;
  views: number;
  status: 'published' | 'review' | 'draft';
  tags: string[];
  fileUrl: string;
  fileName: string;
  createdBy: string;
  createdAt: string;
  updatedAt: string;
  lastUpdate: string;
}

export interface CreateDatasetRequest {
  title: string;
  description: string;
  categoryId: string;
  tags?: string[];
  status?: 'published' | 'review' | 'draft';
}

export interface UpdateDatasetRequest extends Partial<CreateDatasetRequest> {
  id: string;
}

export interface DatasetSearchParams {
  query?: string;
  category?: string;
  format?: string;
  status?: string;
  page?: number;
  limit?: number;
  sortBy?: 'title' | 'downloads' | 'views' | 'createdAt';
  sortOrder?: 'asc' | 'desc';
}

export class DatasetService {
  private useMockService = process.env.NODE_ENV === 'development';

  async getDatasets(params?: DatasetSearchParams): Promise<ApiResponse<Dataset[]>> {
    try {
      // Use mock service in development or when backend is not available
      if (this.useMockService) {
        return await mockDatasetService.getDatasets(params);
      }

      return await apiClient.get<Dataset[]>(
        API_CONFIG.ENDPOINTS.DATASETS.LIST,
        params
      );
    } catch (error: any) {
      console.error('Get datasets error:', error);
      
      // Fallback to mock service if API call fails
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        return await mockDatasetService.getDatasets(params);
      }
      
      throw error;
    }
  }

  async getDataset(id: string): Promise<ApiResponse<Dataset>> {
    try {
      if (this.useMockService) {
        return await mockDatasetService.getDataset(id);
      }

      return await apiClient.get<Dataset>(
        API_CONFIG.ENDPOINTS.DATASETS.GET(id)
      );
    } catch (error: any) {
      console.error('Get dataset error:', error);
      
      // Fallback to mock service
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        return await mockDatasetService.getDataset(id);
      }
      
      throw error;
    }
  }

  async createDataset(data: CreateDatasetRequest): Promise<ApiResponse<Dataset>> {
    try {
      if (this.useMockService) {
        return await mockDatasetService.createDataset(data);
      }

      return await apiClient.post<Dataset>(
        API_CONFIG.ENDPOINTS.DATASETS.CREATE,
        data
      );
    } catch (error: any) {
      console.error('Create dataset error:', error);
      
      // Fallback to mock service
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        return await mockDatasetService.createDataset(data);
      }
      
      throw error;
    }
  }

  async updateDataset(data: UpdateDatasetRequest): Promise<ApiResponse<Dataset>> {
    try {
      if (this.useMockService) {
        return await mockDatasetService.updateDataset(data);
      }

      return await apiClient.put<Dataset>(
        API_CONFIG.ENDPOINTS.DATASETS.UPDATE(data.id),
        data
      );
    } catch (error: any) {
      console.error('Update dataset error:', error);
      
      // Fallback to mock service
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        return await mockDatasetService.updateDataset(data);
      }
      
      throw error;
    }
  }

  async deleteDataset(id: string): Promise<ApiResponse> {
    try {
      if (this.useMockService) {
        return await mockDatasetService.deleteDataset(id);
      }

      return await apiClient.delete(
        API_CONFIG.ENDPOINTS.DATASETS.DELETE(id)
      );
    } catch (error: any) {
      console.error('Delete dataset error:', error);
      
      // Fallback to mock service
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        return await mockDatasetService.deleteDataset(id);
      }
      
      throw error;
    }
  }

  async uploadDatasetFile(datasetId: string, file: File): Promise<ApiResponse<{ fileUrl: string; fileName: string; size: number }>> {
    try {
      if (this.useMockService) {
        return await mockDatasetService.uploadDatasetFile(datasetId, file);
      }

      const formData = new FormData();
      formData.append('file', file);
      formData.append('datasetId', datasetId);

      return await apiClient.upload(
        API_CONFIG.ENDPOINTS.DATASETS.UPLOAD,
        formData
      );
    } catch (error: any) {
      console.error('Upload dataset file error:', error);
      
      // Fallback to mock service
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        return await mockDatasetService.uploadDatasetFile(datasetId, file);
      }
      
      throw error;
    }
  }

  async downloadDataset(id: string): Promise<void> {
    try {
      if (this.useMockService) {
        return await mockDatasetService.downloadDataset(id);
      }

      const response = await fetch(
        `${API_CONFIG.BASE_URL}${API_CONFIG.ENDPOINTS.DATASETS.DOWNLOAD(id)}`,
        {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
          }
        }
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
      console.error('Download dataset error:', error);
      
      // Fallback to mock service
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        return await mockDatasetService.downloadDataset(id);
      }
      
      throw error;
    }
  }

  async searchDatasets(query: string, filters?: Omit<DatasetSearchParams, 'query'>): Promise<ApiResponse<Dataset[]>> {
    try {
      if (this.useMockService) {
        return await mockDatasetService.searchDatasets(query, filters);
      }

      return await apiClient.get<Dataset[]>(
        API_CONFIG.ENDPOINTS.DATASETS.SEARCH,
        { query, ...filters }
      );
    } catch (error: any) {
      console.error('Search datasets error:', error);
      
      // Fallback to mock service
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        return await mockDatasetService.searchDatasets(query, filters);
      }
      
      throw error;
    }
  }

  async getPopularDatasets(limit = 10): Promise<ApiResponse<Dataset[]>> {
    try {
      if (this.useMockService) {
        return await mockDatasetService.getPopularDatasets(limit);
      }

      return await apiClient.get<Dataset[]>(
        API_CONFIG.ENDPOINTS.DATASETS.POPULAR,
        { limit }
      );
    } catch (error: any) {
      console.error('Get popular datasets error:', error);
      
      // Fallback to mock service
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        return await mockDatasetService.getPopularDatasets(limit);
      }
      
      throw error;
    }
  }

  async getRecentDatasets(limit = 10): Promise<ApiResponse<Dataset[]>> {
    try {
      if (this.useMockService) {
        return await mockDatasetService.getRecentDatasets(limit);
      }

      return await apiClient.get<Dataset[]>(
        API_CONFIG.ENDPOINTS.DATASETS.RECENT,
        { limit }
      );
    } catch (error: any) {
      console.error('Get recent datasets error:', error);
      
      // Fallback to mock service
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        return await mockDatasetService.getRecentDatasets(limit);
      }
      
      throw error;
    }
  }
}

export const datasetService = new DatasetService();