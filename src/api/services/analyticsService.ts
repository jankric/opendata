import { apiClient, ApiResponse } from '../client';
import { API_CONFIG } from '../config';

export interface DashboardStats {
  totalDatasets: number;
  totalUsers: number;
  totalDownloads: number;
  totalViews: number;
  monthlyGrowth: {
    datasets: number;
    users: number;
    downloads: number;
    views: number;
  };
}

export interface DatasetAnalytics {
  id: string;
  title: string;
  downloads: number;
  views: number;
  downloadTrend: Array<{ date: string; count: number }>;
  viewTrend: Array<{ date: string; count: number }>;
}

export interface UserAnalytics {
  totalUsers: number;
  activeUsers: number;
  newUsers: number;
  usersByRole: Array<{ role: string; count: number }>;
  usersByDepartment: Array<{ department: string; count: number }>;
  registrationTrend: Array<{ date: string; count: number }>;
}

export interface DownloadAnalytics {
  totalDownloads: number;
  downloadsByCategory: Array<{ category: string; count: number }>;
  downloadsByFormat: Array<{ format: string; count: number }>;
  downloadTrend: Array<{ date: string; count: number }>;
  topDatasets: Array<{ id: string; title: string; downloads: number }>;
}

export class AnalyticsService {
  async getDashboardStats(): Promise<ApiResponse<DashboardStats>> {
    try {
      return await apiClient.get<DashboardStats>(
        API_CONFIG.ENDPOINTS.ANALYTICS.DASHBOARD
      );
    } catch (error) {
      console.error('Get dashboard stats error:', error);
      throw error;
    }
  }

  async getDatasetAnalytics(params?: {
    startDate?: string;
    endDate?: string;
    datasetId?: string;
  }): Promise<ApiResponse<DatasetAnalytics[]>> {
    try {
      return await apiClient.get<DatasetAnalytics[]>(
        API_CONFIG.ENDPOINTS.ANALYTICS.DATASETS,
        params
      );
    } catch (error) {
      console.error('Get dataset analytics error:', error);
      throw error;
    }
  }

  async getUserAnalytics(params?: {
    startDate?: string;
    endDate?: string;
  }): Promise<ApiResponse<UserAnalytics>> {
    try {
      return await apiClient.get<UserAnalytics>(
        API_CONFIG.ENDPOINTS.ANALYTICS.USERS,
        params
      );
    } catch (error) {
      console.error('Get user analytics error:', error);
      throw error;
    }
  }

  async getDownloadAnalytics(params?: {
    startDate?: string;
    endDate?: string;
  }): Promise<ApiResponse<DownloadAnalytics>> {
    try {
      return await apiClient.get<DownloadAnalytics>(
        API_CONFIG.ENDPOINTS.ANALYTICS.DOWNLOADS,
        params
      );
    } catch (error) {
      console.error('Get download analytics error:', error);
      throw error;
    }
  }
}

export const analyticsService = new AnalyticsService();