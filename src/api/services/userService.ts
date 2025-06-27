import { apiClient, ApiResponse } from '../client';
import { API_CONFIG } from '../config';

export interface User {
  id: string;
  name: string;
  email: string;
  phone: string;
  role: 'admin' | 'editor' | 'viewer';
  department: string;
  status: 'active' | 'inactive';
  avatar?: string;
  lastLogin?: string;
  createdAt: string;
  updatedAt: string;
}

export interface CreateUserRequest {
  name: string;
  email: string;
  phone: string;
  role: 'admin' | 'editor' | 'viewer';
  department: string;
  status?: 'active' | 'inactive';
  password: string;
}

export interface UpdateUserRequest extends Partial<Omit<CreateUserRequest, 'password'>> {
  id: string;
  password?: string;
}

export interface UserSearchParams {
  query?: string;
  role?: string;
  department?: string;
  status?: string;
  page?: number;
  limit?: number;
  sortBy?: 'name' | 'email' | 'createdAt' | 'lastLogin';
  sortOrder?: 'asc' | 'desc';
}

export class UserService {
  async getUsers(params?: UserSearchParams): Promise<ApiResponse<User[]>> {
    try {
      return await apiClient.get<User[]>(
        API_CONFIG.ENDPOINTS.USERS.LIST,
        params
      );
    } catch (error) {
      console.error('Get users error:', error);
      throw error;
    }
  }

  async getUser(id: string): Promise<ApiResponse<User>> {
    try {
      return await apiClient.get<User>(
        API_CONFIG.ENDPOINTS.USERS.GET(id)
      );
    } catch (error) {
      console.error('Get user error:', error);
      throw error;
    }
  }

  async createUser(data: CreateUserRequest): Promise<ApiResponse<User>> {
    try {
      return await apiClient.post<User>(
        API_CONFIG.ENDPOINTS.USERS.CREATE,
        data
      );
    } catch (error) {
      console.error('Create user error:', error);
      throw error;
    }
  }

  async updateUser(data: UpdateUserRequest): Promise<ApiResponse<User>> {
    try {
      return await apiClient.put<User>(
        API_CONFIG.ENDPOINTS.USERS.UPDATE(data.id),
        data
      );
    } catch (error) {
      console.error('Update user error:', error);
      throw error;
    }
  }

  async deleteUser(id: string): Promise<ApiResponse> {
    try {
      return await apiClient.delete(
        API_CONFIG.ENDPOINTS.USERS.DELETE(id)
      );
    } catch (error) {
      console.error('Delete user error:', error);
      throw error;
    }
  }
}

export const userService = new UserService();