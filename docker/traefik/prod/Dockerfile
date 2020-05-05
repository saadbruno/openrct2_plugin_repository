FROM scratch
COPY --from=traefik:v1.7.24-alpine /etc/ssl/certs/ca-certificates.crt /etc/ssl/certs/
COPY --from=traefik:v1.7.24-alpine /usr/share/zoneinfo /usr/share/
COPY --from=traefik:v1.7.24-alpine /usr/local/bin/traefik /

EXPOSE 4000
VOLUME ["/tmp"]
ENTRYPOINT ["/traefik"]

COPY traefik/prod/traefik.prod.toml /etc/traefik/traefik.toml
