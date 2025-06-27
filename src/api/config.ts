// API Configuration
export const API_CONFIG = {
  BASE_URL: process.env.NODE_ENV === 'production' 
    ? 'https://api-opendata.gorontalokab.go.id' 
    : 'http://localhost:8000/api/v1',
  ENDPOINTS: {
    // Authentication
    AUTH: {
      LOGIN: '/auth/login',
      LOGOUT: '/auth/logout',
      REFRESH: '/auth/refresh',
      PROFILE: '/auth/profile'
    },
    // Datasets (Public API)
    DATASETS: {
      LIST: '/datasets',
      CREATE: '/datasets',
      GET: (id: string) => `/datasets/${id}`,
      UPDATE: (id: string) => `/datasets/${id}`,
      DELETE: (id: string) => `/datasets/${id}`,
      UPLOAD: '/datasets/upload',
      DOWNLOAD: (id: string) => `/datasets/${id}/download`,
      SEARCH: '/datasets/search',
      POPULAR: '/datasets/popular',
      RECENT: '/datasets/recent'
    },
    // Resources
    RESOURCES: {
      DOWNLOAD: (id: string) => `/resources/${id}/download`,
      PREVIEW: (id: string) => `/resources/${id}/preview`
    },
    // Categories
    CATEGORIES: {
      LIST: '/categories',
      CREATE: '/categories',
      GET: (id: string) => `/categories/${id}`,
      UPDATE: (id: string) => `/categories/${id}`,
      DELETE: (id: string) => `/categories/${id}`
    },
    // Organizations
    ORGANIZATIONS: {
      LIST: '/organizations',
      CREATE: '/organizations',
      GET: (id: string) => `/organizations/${id}`,
      UPDATE: (id: string) => `/organizations/${id}`,
      DELETE: (id: string) => `/organizations/${id}`
    },
    // Tags
    TAGS: {
      LIST: '/tags'
    },
    // Public Statistics
    STATS: '/stats'
  }
};

export const API_HEADERS = {
  'Content-Type': 'application/json',
  'Accept': 'application/json'
};