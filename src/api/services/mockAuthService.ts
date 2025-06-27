import { ApiResponse } from '../client';
import { LoginCredentials, User, AuthResponse } from './authService';

// Mock user data
const MOCK_USERS: User[] = [
  {
    id: '1',
    name: 'Administrator',
    email: 'admin@gorontalokab.go.id',
    role: 'admin',
    department: 'IT Department',
    avatar: 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop',
    createdAt: '2024-01-01T00:00:00Z',
    lastLogin: new Date().toISOString()
  }
];

const MOCK_CREDENTIALS = {
  'admin@gorontalokab.go.id': 'admin123'
};

// Simulate network delay
const delay = (ms: number) => new Promise(resolve => setTimeout(resolve, ms));

export class MockAuthService {
  private currentUser: User | null = null;
  private token: string | null = null;

  async login(credentials: LoginCredentials): Promise<ApiResponse<AuthResponse>> {
    await delay(800); // Simulate network delay

    const { email, password } = credentials;
    
    // Check credentials
    if (MOCK_CREDENTIALS[email as keyof typeof MOCK_CREDENTIALS] !== password) {
      throw new Error('Email atau password tidak valid');
    }

    // Find user
    const user = MOCK_USERS.find(u => u.email === email);
    if (!user) {
      throw new Error('User tidak ditemukan');
    }

    // Generate mock token
    this.token = `mock-token-${Date.now()}`;
    this.currentUser = { ...user, lastLogin: new Date().toISOString() };

    // Store in localStorage for persistence
    localStorage.setItem('auth_token', this.token);
    localStorage.setItem('current_user', JSON.stringify(this.currentUser));

    const authResponse: AuthResponse = {
      user: this.currentUser,
      token: this.token,
      refreshToken: `refresh-${this.token}`
    };

    return {
      success: true,
      data: authResponse,
      message: 'Login berhasil'
    };
  }

  async logout(): Promise<ApiResponse> {
    await delay(300);

    this.currentUser = null;
    this.token = null;
    
    // Clear localStorage
    localStorage.removeItem('auth_token');
    localStorage.removeItem('current_user');

    return {
      success: true,
      message: 'Logout berhasil'
    };
  }

  async getProfile(): Promise<ApiResponse<User>> {
    await delay(300);

    // Try to restore from localStorage
    if (!this.currentUser) {
      const storedUser = localStorage.getItem('current_user');
      const storedToken = localStorage.getItem('auth_token');
      
      if (storedUser && storedToken) {
        this.currentUser = JSON.parse(storedUser);
        this.token = storedToken;
      }
    }

    if (!this.currentUser || !this.token) {
      throw new Error('User tidak terautentikasi');
    }

    return {
      success: true,
      data: this.currentUser,
      message: 'Profile berhasil diambil'
    };
  }

  async refreshToken(): Promise<ApiResponse<AuthResponse>> {
    await delay(300);

    if (!this.currentUser || !this.token) {
      throw new Error('Token tidak valid');
    }

    // Generate new token
    const newToken = `mock-token-${Date.now()}`;
    this.token = newToken;

    // Update localStorage
    localStorage.setItem('auth_token', newToken);

    const authResponse: AuthResponse = {
      user: this.currentUser,
      token: newToken,
      refreshToken: `refresh-${newToken}`
    };

    return {
      success: true,
      data: authResponse,
      message: 'Token berhasil diperbarui'
    };
  }

  // Helper method to check if user is authenticated
  isAuthenticated(): boolean {
    const storedToken = localStorage.getItem('auth_token');
    return !!(this.token || storedToken);
  }

  // Helper method to get current user
  getCurrentUser(): User | null {
    if (!this.currentUser) {
      const storedUser = localStorage.getItem('current_user');
      if (storedUser) {
        this.currentUser = JSON.parse(storedUser);
      }
    }
    return this.currentUser;
  }
}

export const mockAuthService = new MockAuthService();