# Source recovery

This snapshot preserves local legacy application source. Dependencies, generated files, datasets, media, logs, database dumps and deployment credentials are excluded.

Install dependencies from the package manifests. Configure the environment variables listed in `.env.example` before use. Deployment-specific hosts are replaced with `example.invalid`; supply the intended service configuration when adapting this legacy code. No running services were contacted during recovery.

Validation: static syntax and credential scans only; this snapshot is not a production deployment.
