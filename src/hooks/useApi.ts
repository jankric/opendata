import { useState, useEffect, useCallback } from 'react';
import { ApiResponse, ApiError } from '../api/client';

export interface UseApiState<T> {
  data: T | null;
  loading: boolean;
  error: ApiError | null;
  refetch: () => Promise<void>;
}

export function useApi<T>(
  apiCall: () => Promise<ApiResponse<T>>,
  dependencies: any[] = []
): UseApiState<T> {
  const [data, setData] = useState<T | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<ApiError | null>(null);

  const fetchData = useCallback(async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await apiCall();
      
      if (response.success && response.data) {
        setData(response.data);
      } else {
        setError({
          message: response.message || 'Unknown error occurred',
          status: 0
        });
      }
    } catch (err) {
      setError(err as ApiError);
    } finally {
      setLoading(false);
    }
  }, dependencies);

  useEffect(() => {
    fetchData();
  }, [fetchData]);

  return {
    data,
    loading,
    error,
    refetch: fetchData
  };
}

export function useApiMutation<T, P = any>() {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<ApiError | null>(null);

  const mutate = useCallback(async (
    apiCall: (params: P) => Promise<ApiResponse<T>>,
    params: P
  ): Promise<T | null> => {
    try {
      setLoading(true);
      setError(null);
      const response = await apiCall(params);
      
      if (response.success && response.data) {
        return response.data;
      } else {
        const error = {
          message: response.message || 'Unknown error occurred',
          status: 0
        };
        setError(error);
        throw error;
      }
    } catch (err) {
      const error = err as ApiError;
      setError(error);
      throw error;
    } finally {
      setLoading(false);
    }
  }, []);

  return {
    mutate,
    loading,
    error
  };
}