#!/bin/sh
set -e

# Fix EFS volume permissions based on lagoon.persistent labels
#
# Two approaches based on container configuration:
# 1. VOLUME_USER set: Use secure permissions with UID/GID 1000 (requires container remapping)
# 2. VOLUME_USER unset: Fall back to world-writable (777/666) for compatibility

echo "Fixing file permissions for EFS volumes..."

# Check if containers have been configured with UID/GID remapping
if [ -n "$VOLUME_USER" ]; then
    echo "🔒 VOLUME_USER detected: Using secure permissions with UID/GID 1000"
    echo "   (Assumes containers remap www-data to UID/GID 1000 for EFS compatibility)"
    PERMISSION_MODE="secure"
else
    echo "⚠️  VOLUME_USER not set: Using world-writable permissions for compatibility"
    echo "   (Consider setting VOLUME_USER and remapping container users to UID/GID 1000)"
    PERMISSION_MODE="compatible"
fi

# Persistent volume paths from docker-compose lagoon.persistent labels
for path in "/app/docroot/sites/default/files/"; do
    if [ -d "$path" ]; then
        echo "Fixing permissions for: $path"

        if [ "$PERMISSION_MODE" = "secure" ]; then
            # Set secure directory permissions (755 = rwxr-xr-x)
            find "$path" -type d -exec chmod 755 {} + 2>/dev/null || true

            # Set secure file permissions (644 = rw-r--r--)
            find "$path" -type f -exec chmod 644 {} + 2>/dev/null || true

            echo "✓ Secure permissions applied: $path (dirs: 755, files: 644)"
        else
            # Compatible approach: World-writable fallback

            # Make directories world-writable (since we can't chown to unknown web server user)
            find "$path" -type d -exec chmod 777 {} + 2>/dev/null || true

            # Make files world-readable/writable (since we can't chown to unknown web server user)
            find "$path" -type f -exec chmod 666 {} + 2>/dev/null || true

            echo "✓ Compatible permissions applied: $path (dirs: 777, files: 666)"
        fi
    else
        echo "Path not found (may not be mounted yet): $path"
    fi
done

echo "Permission fixing completed for 1 volumes ($PERMISSION_MODE mode)"
