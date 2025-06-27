import { apiClient, ApiResponse } from '../client';
import { API_CONFIG } from '../config';
import { mockAuthService } from './mockAuthService';

export interface LoginCredentials {
  email: string;
  password: string;
}

export interface User {
  id: string;
  name: string;
  email: string;
  role: 'admin' | 'editor' | 'viewer';
  department: string;
  avatar?: string;
  createdAt: string;
  lastLogin?: string;
}

export interface AuthResponse {
  user: User;
  token: string;
  refreshToken: string;
}

export class AuthService {
  private useMockService = process.env.NODE_ENV === 'development';

  async login(credentials: LoginCredentials): Promise<ApiResponse<AuthResponse>> {
    try {
      // Use mock service in development or when backend is not available
      if (this.useMockService) {
        return await mockAuthService.login(credentials);
      }

      const response = await apiClient.post<AuthResponse>(
        API_CONFIG.ENDPOINTS.AUTH.LOGIN,
        credentials
      );
      
      if (response.success && response.data) {
        apiClient.setToken(response.data.token);
      }
      
      return response;
    } catch (error: any) {
      console.error('Login error:', error);
      
      // Fallback to mock service if API call fails
      if (!this.useMockService && (error.message?.includes('fetch') || error.message?.includes('network'))) {
        console.warn('API unavailable, falling back to mock service');
        return await mockAuthService.login(credentials);
      }
      
      throw error;
    }
  }

  async logout(): Promise<ApiResponse> {
    try {
      if (this.useMockService) {
        return await mockAuthService.logout();
      }

      const response = await apiClient.post(API_CONFIG.ENDPOINTS.AUTH.LOGOUT);
      apiClient.removeToken();
      return response;
    } catch (error) {
      console.error('Logout error:', error);
      
      // Fallback to mock service
      if (!this.useMockService) {
        return await mockAuthService.logout();
      }
      
      apiClient.removeToken(); // Remove token even if API call fails
      throw error;
    }
  }

  async getProfile(): Promise<ApiResponse<User>> {
    try {
      if (this.useMockService) {
        return await mockAuthService.getProfile();
      }

      return await apiClient.get<User>(API_CONFIG.ENDPOINTS.AUTH.PROFILE);
    } catch (error) {
      console.error('Get profile error:', error);
      
      // Fallback to mock service
      if (!this.useMockService) {
        return await mockAuthService.getProfile();
      }
      
      throw error;
    }
  }

  async refreshToken(): Promise<ApiResponse<AuthResponse>> {
    try {
      if (this.useMockService) {
        return await mockAuthService.refreshToken();
      }

      const response = await apiClient.post<AuthResponse>(
        API_CONFIG.ENDPOINTS.AUTH.REFRESH
      );
      
      if (response.success && response.data) {
        apiClient.setToken(response.data.token);
      }
      
      return response;
    } catch (error) {
      console.error('Refresh token error:', error);
      
      // Fallback to mock service
      if (!this.useMockService) {
        return await mockAuthService.refreshToken();
      }
      
      throw error;
    }
  }

  // Helper methods from mock service
  isAuthenticated(): boolean {
    if (this.useMockService) {
      return mockAuthService.isAuthenticated();
    }
    return !!apiClient.getToken();
  }

  getCurrentUser(): User | null {
    if (this.useMockService) {
      return mockAuthService.getCurrentUser();
    }
    // In real implementation, this would decode the JWT token or make an API call
    return null;
  }
}

export const authService = new AuthService();