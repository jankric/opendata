-- Create extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";
CREATE EXTENSION IF NOT EXISTS "unaccent";

-- Create full-text search configuration for Indonesian
CREATE TEXT SEARCH CONFIGURATION indonesian (COPY = simple);