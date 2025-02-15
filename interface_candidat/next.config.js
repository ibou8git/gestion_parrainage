/** @type {import('next').NextConfig} */
const nextConfig = {
    experimental: {
      appDir: true,
    },
    trailingSlash: false,
    reactStrictMode: true,
    output: 'standalone',
  };
  
  module.exports = nextConfig;
  