# Render Deployment Guide

This project is configured to deploy on [Render](https://render.com) using Docker.

## Prerequisites

1. A Render account
2. Your code pushed to a Git repository (GitHub, GitLab, or Bitbucket)

## Deployment Steps

### Option 1: Using render.yaml (Recommended)

1. Push your code to your Git repository
2. In Render Dashboard, click "New +" → "Blueprint"
3. Connect your repository
4. Render will automatically detect `render.yaml` and configure the service
5. Review and deploy

### Option 2: Manual Configuration

1. **Create a PostgreSQL Database:**
   - In Render Dashboard, click "New +" → "PostgreSQL"
   - Copy the **Internal Database URL** (not the external one)

2. **Create a Web Service:**
   - Click "New +" → "Web Service"
   - Connect your Git repository
   - Configure:
     - **Name:** project-management-api
     - **Runtime:** Docker
     - **Dockerfile Path:** `Dockerfile` (or leave default)
     - **Docker Context:** `.` (or leave default)

3. **Set Environment Variables:**
   Add these in the "Environment" section:
   
   | Key | Value |
   |-----|-------|
   | `APP_ENV` | `production` |
   | `APP_DEBUG` | `false` |
   | `DB_CONNECTION` | `pgsql` |
   | `DATABASE_URL` | Your **internal** database URL from step 1 |
   | `APP_KEY` | Run `php artisan key:generate --show` locally and paste the output |

4. **Deploy:**
   - Click "Create Web Service"
   - Render will build and deploy your application

## What Happens During Deployment

The deployment script (`docker/render-deploy.sh`) automatically runs:

1. ✅ Composer install (installs PHP dependencies)
2. ✅ Config cache (optimizes configuration)
3. ✅ Route cache (optimizes routing)
4. ✅ View cache (optimizes views)
5. ✅ Database migrations (runs automatically)

## Environment Variables

Make sure to set these environment variables in Render:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `DB_CONNECTION=pgsql`
- `DATABASE_URL` (from your PostgreSQL database - use **internal** URL)
- `APP_KEY` (generate with `php artisan key:generate --show`)

### Additional Variables (if needed)

- `APP_URL` - Your Render service URL (e.g., `https://your-app.onrender.com`)
- `CACHE_DRIVER` - `redis` or `file` (default)
- `SESSION_DRIVER` - `redis` or `file` (default)
- `QUEUE_CONNECTION` - `redis` or `sync` (default)

## HTTPS Configuration

The application automatically forces HTTPS in production via `AppServiceProvider`. This is required for Render deployments to avoid mixed content warnings.

## Troubleshooting

### Build Fails

- Check Dockerfile syntax
- Verify the base image tag `dwchiang/nginx-php-fpm:8.2` exists
- Check build logs in Render dashboard

### Application Won't Start

- Verify all environment variables are set
- Check application logs in Render dashboard
- Ensure `APP_KEY` is set correctly
- Verify database connection using internal `DATABASE_URL`

### Database Connection Issues

- Use the **internal** database URL, not external
- Ensure `DB_CONNECTION=pgsql` is set
- Check database is in the same region as your web service

### Assets Not Loading

- Ensure `APP_URL` is set to your Render service URL
- Check that HTTPS is being forced (already configured)
- Verify `ASSET_URL` if using CDN

## Health Check

The application includes a health check endpoint at `/up` which Render uses to verify the service is running.

## Notes

- The Docker image uses `dwchiang/nginx-php-fpm:8.2` which combines Nginx and PHP-FPM
- Frontend assets should be built before deployment (or uncomment the build step in Dockerfile)
- Storage and cache directories are writable by the web server
- Migrations run automatically on each deployment

