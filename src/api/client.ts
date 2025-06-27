import { API_CONFIG, API_HEADERS } from './config';

export interface ApiResponse<T = any> {
  success: boolean;
  data?: T;
  message?: string;
  error?: string;
  pagination?: {
    page: number;
    limit: number;
    total: number;
    totalPages: number;
  };
}

export interface ApiError {
  message: string;
  status: number;
  code?: string;
}

class ApiClient {
  private baseURL: string;
  private token: string | null = null;

  constructor() {
    this.baseURL = API_CONFIG.BASE_URL;
    this.token = localStorage.getItem('auth_token');
  }

  setToken(token: string) {
    this.token = token;
    localStorage.setItem('auth_token', token);
  }

  removeToken() {
    this.token = null;
    localStorage.removeItem('auth_token');
  }

  getToken(): string | null {
    return this.token;
  }

  private getHeaders(): HeadersInit {
    const headers: HeadersInit = { ...API_HEADERS };
    
    if (this.token) {
      headers['Authorization'] = `Bearer ${this.token}`;
    }
    
    return headers;
  }

  private async handleResponse<T>(response: Response): Promise<ApiResponse<T>> {
    const contentType = response.headers.get('content-type');
    
    if (contentType && contentType.includes('application/json')) {
      const data = await response.json();
      
      if (!response.ok) {
        throw {
          message: data.message || 'An error occurred',
          status: response.status,
          code: data.code
        } as ApiError;
      }
      
      return data;
    } else {
      if (!response.ok) {
        throw {
          message: `HTTP Error: ${response.status}`,
          status: response.status
        } as ApiError;
      }
      
      return {
        success: true,
        data: await response.text() as any
      };
    }
  }

  async get<T>(endpoint: string, params?: Record<string, any>): Promise<ApiResponse<T>> {
    const url = new URL(`${this.baseURL}${endpoint}`);
    
    if (params) {
      Object.keys(params).forEach(key => {
        if (params[key] !== undefined && params[key] !== null) {
          url.searchParams.append(key, params[key].toString());
        }
      });
    }

    const response = await fetch(url.toString(), {
      method: 'GET',
      headers: this.getHeaders()
    });

    return this.handleResponse<T>(response);
  }

  async post<T>(endpoint: string, data?: any): Promise<ApiResponse<T>> {
    const response = await fetch(`${this.baseURL}${endpoint}`, {
      method: 'POST',
      headers: this.getHeaders(),
      body: data instanceof FormData ? data : JSON.stringify(data)
    });

    return this.handleResponse<T>(response);
  }

  async put<T>(endpoint: string, data?: any): Promise<ApiResponse<T>> {
    const response = await fetch(`${this.baseURL}${endpoint}`, {
      method: 'PUT',
      headers: this.getHeaders(),
      body: JSON.stringify(data)
    });

    return this.handleResponse<T>(response);
  }

  async delete<T>(endpoint: string): Promise<ApiResponse<T>> {
    const response = await fetch(`${this.baseURL}${endpoint}`, {
      method: 'DELETE',
      headers: this.getHeaders()
    });

    return this.handleResponse<T>(response);
  }

  async upload<T>(endpoint: string, formData: FormData): Promise<ApiResponse<T>> {
    const headers = { ...this.getHeaders() };
    delete headers['Content-Type']; // Let browser set content-type for FormData

    const response = await fetch(`${this.baseURL}${endpoint}`, {
      method: 'POST',
      headers,
      body: formData
    });

    return this.handleResponse<T>(response);
  }
}

export const apiClient = new ApiClient();