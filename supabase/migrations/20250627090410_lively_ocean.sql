-- Initialize PostgreSQL database for Open Data Portal
-- This script runs when the PostgreSQL container starts for the first time

-- Create extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";
CREATE EXTENSION IF NOT EXISTS "unaccent";

-- Create full-text search configuration for Indonesian
CREATE TEXT SEARCH CONFIGURATION indonesian (COPY = simple);

-- Set timezone
SET timezone = 'Asia/Makassar';

-- Create database user if not exists (optional, since we're using postgres user)
-- DO
-- $do$
-- BEGIN
--    IF NOT EXISTS (
--       SELECT FROM pg_catalog.pg_roles
--       WHERE  rolname = 'opendata_user') THEN
--       CREATE ROLE opendata_user LOGIN PASSWORD 'opendata_password';
--    END IF;
-- END
-- $do$;

-- Grant privileges
-- GRANT ALL PRIVILEGES ON DATABASE opendata_portal TO opendata_user;