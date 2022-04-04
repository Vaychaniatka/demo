FROM nginx:alpine

RUN { \
    # Increased the number of worker_processes from any number to 8
    sed -i 's/\(worker_processes\s*\)[0-9]*;/\18;/' /etc/nginx/nginx.conf; \
    # Increased the number of worker_connections from any number to 4096
    sed -i 's/\(worker_connections\s*\)[0-9]*;/\14096;/' /etc/nginx/nginx.conf; \
}
