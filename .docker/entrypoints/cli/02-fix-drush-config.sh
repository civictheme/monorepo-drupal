#!/bin/sh
set -e

# Fix Drush configuration for Quant Cloud compatibility
# Replace LAGOON_ROUTE with QUANT_ROUTE in drush.yml configuration

DRUSH_CONFIG_FILE="/home/.drush/drush.yml"

echo "Checking for Drush configuration file..."

if [ -f "${DRUSH_CONFIG_FILE}" ]; then
    echo "Found Drush config file: ${DRUSH_CONFIG_FILE}"
    
    # Check if the file contains LAGOON_ROUTE
    if grep -q "env.LAGOON_ROUTE" "${DRUSH_CONFIG_FILE}"; then
        echo "Updating LAGOON_ROUTE to QUANT_ROUTE in Drush config"
        
        # Create a backup of the original file
        cp "${DRUSH_CONFIG_FILE}" "${DRUSH_CONFIG_FILE}.bak"
        
        # Replace env.LAGOON_ROUTE with env.QUANT_ROUTE
        sed -i 's/env\.LAGOON_ROUTE/env.QUANT_ROUTE/g' "${DRUSH_CONFIG_FILE}"
        
        echo "✓ Successfully updated Drush configuration for Quant Cloud"
    else
        echo "No LAGOON_ROUTE references found in Drush config - no changes needed"
    fi
else
    echo "Drush config file not found at ${DRUSH_CONFIG_FILE} - skipping"
fi

echo "Drush configuration check completed"
