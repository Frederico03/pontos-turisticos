# syntax=docker/dockerfile:1.4
FROM nginx:latest

# Instalar curl para healthcheck (com cache mount)
RUN --mount=type=cache,target=/var/cache/apt,sharing=locked \
    --mount=type=cache,target=/var/lib/apt,sharing=locked \
    apt-get update && apt-get install -y curl \
    && rm -rf /var/lib/apt/lists/*
