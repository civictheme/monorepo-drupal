#!/usr/bin/env node

/**
 * CivicTheme UI Kit Version Change Tool.
 *
 * This script helps developers switch between different versions of the CivicTheme UI Kit:
 * - NPM released version
 * - Main branch of the UI Kit repository
 * - Development/feature branch of the UI Kit repository
 */

import select from '@inquirer/select';
import input from '@inquirer/input';
import search from '@inquirer/search';
import axios from 'axios';
import { promises as fs } from 'fs';
import { execSync } from 'child_process';
import path from 'path';
import { fileURLToPath } from 'url';

// Get the directory of the current module
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Define paths relative to the root of the project
const ROOT_DIR = path.resolve(__dirname, '../..');
const ROOT_PACKAGE_JSON = path.join(ROOT_DIR, 'package.json');
const THEME_PACKAGE_JSON = path.join(ROOT_DIR, 'web/themes/contrib/civictheme/package.json');

// GitHub repository information
const GITHUB_API_URL = 'https://api.github.com/repos/civictheme/uikit/branches';
const GITHUB_COMMITS_API_URL = 'https://api.github.com/repos/civictheme/uikit/commits/';
const GITHUB_REPO_URL = 'github:civictheme/uikit';
const NPM_PACKAGE_NAME = '@civictheme/uikit';

/**
 * Main function to run the application.
 */
async function main() {
  try {
    console.log('🌟 CivicTheme UI Kit Version Change Tool 🌟');

    // Ask what CivicTheme UI Kit to install
    const installType = await select({
      message: 'What CivicTheme UI Kit do you want to install?',
      choices: [
        { name: 'Release', value: 'Release' },
        { name: 'Dev', value: 'Dev' }
      ]
    });

    if (installType === 'Release') {
      await handleReleaseInstallation();
    } else {
      await handleDevInstallation();
    }

  } catch (error) {
    console.error('Error:', error.message);
    process.exit(1);
  }
}

/**
 * Handle the installation of a released version from NPM.
 */
async function handleReleaseInstallation() {
  try {
    // Get available versions from NPM (simulated for now)
    console.log('Fetching available versions from NPM...');

    // For a real implementation, you would fetch the versions from NPM registry
    // Here we're using a default latest version
    const defaultVersion = 'latest';

    const version = await input({
      message: 'Enter the version to install (e.g., 1.0.0, latest):',
      default: defaultVersion
    });

    // Update package.json files
    const versionString = version === 'latest' ? '' : `@${version}`;
    await updatePackageJsonFiles(`${NPM_PACKAGE_NAME}${versionString}`, null);

    // Run npm install
    await installDependencies();

    console.log(`✅ Successfully installed ${NPM_PACKAGE_NAME}${versionString}`);
  } catch (error) {
    console.error('Error during release installation:', error.message);
    throw error;
  }
}

/**
 * Handle the installation from a development branch.
 */
async function handleDevInstallation() {
  try {
    // Fetch branches from GitHub repository
    console.log('Fetching branches from GitHub...');
    const branches = await fetchGitHubBranches();
    if (!branches || branches.length === 0) {
      throw new Error('No branches found or error fetching branches');
    }

    // Extract branch names
    const branchNames = branches.map(branch => branch.name);

    // Make sure 'main' is at the top of the list if it exists
    const mainIndex = branchNames.indexOf('main');
    if (mainIndex > 0) {
      branchNames.splice(mainIndex, 1);
      branchNames.unshift('main');
    }
    // Use search prompt for branch selection
    const branch = await search({
      message: 'Search for a branch to install:',
      source: async (input) => {
        return input === undefined ? branchNames : branchNames
          .filter((branch) => {
            return branch.toLowerCase().includes(input.toLowerCase())
          });
      },
      pageSize: 50
    });

    // Fetch the latest commit hash for the selected branch
    const latestCommitHash = await fetchLatestCommitHash(branch);
    
    // Update package.json files with the GitHub branch and commit hash
    await updatePackageJsonFiles(`${GITHUB_REPO_URL}#${branch}`, latestCommitHash);

    // Run npm install
    await installDependencies();

    console.log(`✅ Successfully installed ${GITHUB_REPO_URL}#${branch} (commit ${latestCommitHash.substring(0, 8)}...)`);
  } catch (error) {
    console.error('Error during dev installation:', error.message);
    throw error;
  }
}

/**
 * Fetch branches from GitHub repository.
 */
async function fetchGitHubBranches() {
  try {
    const response = await axios.get(GITHUB_API_URL, {
      headers: {
        'Accept': 'application/vnd.github+json'
      }
    });
    return response.data;
  } catch (error) {
    console.error('Error fetching branches:', error.message);
    console.log('Failed to fetch branches, using an empty list.');
    return [];
  }
}

/**
 * Fetch latest commit hash for a specific branch.
 */
async function fetchLatestCommitHash(branch) {
  try {
    console.log(`Fetching latest commit hash for branch '${branch}'...`);
    const response = await axios.get(`${GITHUB_COMMITS_API_URL}${branch}`, {
      headers: {
        'Accept': 'application/vnd.github+json'
      }
    });
    
    if (response.data && response.data.sha) {
      console.log(`Found latest commit: ${response.data.sha.substring(0, 8)}...`);
      return response.data.sha;
    }
    
    throw new Error('Could not retrieve commit hash from response');
  } catch (error) {
    console.error('Error fetching latest commit hash:', error.message);
    throw error;
  }
}

/**
 * Update package.json files with the new UI Kit version.
 * If commitHash is provided, it will update the branch reference to use the commit hash.
 */
async function updatePackageJsonFiles(uikitVersion, commitHash = null) {
  try {
    // Update root package.json
    await updatePackageJson(ROOT_PACKAGE_JSON, uikitVersion, commitHash);
    console.log(`Updated ${ROOT_PACKAGE_JSON}`);

    // Update theme package.json if it exists
    try {
      await fs.access(THEME_PACKAGE_JSON);
      await updatePackageJson(THEME_PACKAGE_JSON, uikitVersion, commitHash);
      console.log(`Updated ${THEME_PACKAGE_JSON}`);
    } catch (error) {
      console.log(`Theme package.json not found at ${THEME_PACKAGE_JSON}, skipping`);
    }
    
    // If we have a commit hash, ensure the package-lock.json files are updated too
    if (commitHash) {
      try {
        console.log('Updating package-lock.json files with latest commit hash...');
        
        // Update root package-lock.json if it exists
        const rootPackageLockPath = path.join(ROOT_DIR, 'package-lock.json');
        try {
          await fs.access(rootPackageLockPath);
          console.log(`Updating ${rootPackageLockPath} with commit hash ${commitHash.substring(0, 8)}...`);
          
          // We'll use sed-like pattern replacement via execSync
          execSync(`sed -i 's|civictheme/uikit#[a-f0-9]\\{40\\}|civictheme/uikit#${commitHash}|g' ${rootPackageLockPath}`);
          execSync(`sed -i 's|git+ssh://git@github.com/civictheme/uikit.git#[a-f0-9]\\{40\\}|git+ssh://git@github.com/civictheme/uikit.git#${commitHash}|g' ${rootPackageLockPath}`);
        } catch (error) {
          console.log(`Root package-lock.json not found or couldn't be updated, will be created during npm install`);
        }
        
        // Update theme package-lock.json if it exists
        const themePackageLockPath = path.join(ROOT_DIR, 'web/themes/contrib/civictheme/package-lock.json');
        try {
          await fs.access(themePackageLockPath);
          console.log(`Updating ${themePackageLockPath} with commit hash ${commitHash.substring(0, 8)}...`);
          
          execSync(`sed -i 's|civictheme/uikit#[a-f0-9]\\{40\\}|civictheme/uikit#${commitHash}|g' ${themePackageLockPath}`);
          execSync(`sed -i 's|git+ssh://git@github.com/civictheme/uikit.git#[a-f0-9]\\{40\\}|git+ssh://git@github.com/civictheme/uikit.git#${commitHash}|g' ${themePackageLockPath}`);
        } catch (error) {
          console.log(`Theme package-lock.json not found or couldn't be updated, will be created during npm install`);
        }
      } catch (error) {
        console.error('Error updating package-lock.json files:', error.message);
        // Not throwing here as this is an enhancement, and we want to continue with npm install
        console.log('Continuing with installation...');
      }
    }
  } catch (error) {
    console.error('Error updating package.json files:', error.message);
    throw error;
  }
}

/**
 * Update a specific package.json file with the new UI Kit version.
 * If commitHash is provided, it will be used to update GitHub references.
 */
async function updatePackageJson(filePath, uikitVersion, commitHash = null) {
  try {
    // Read the package.json file
    const data = await fs.readFile(filePath, 'utf8');
    const packageJson = JSON.parse(data);

    // Update the @civictheme/uikit dependency
    if (!packageJson.dependencies) {
      packageJson.dependencies = {};
    }

    // If we have a commit hash and this is a GitHub version, update to use the commit hash
    if (commitHash && uikitVersion.includes('github:civictheme/uikit#')) {
      packageJson.dependencies[NPM_PACKAGE_NAME] = `github:civictheme/uikit#${commitHash}`;
      console.log(`Setting ${NPM_PACKAGE_NAME} to use commit hash ${commitHash.substring(0, 8)}...`);
    } else {
      packageJson.dependencies[NPM_PACKAGE_NAME] = uikitVersion;
    }

    // Write back to the file with proper formatting
    await fs.writeFile(
      filePath,
      JSON.stringify(packageJson, null, 2) + '\n',
      'utf8'
    );
  } catch (error) {
    console.error(`Error updating ${filePath}:`, error.message);
    throw error;
  }
}

/**
 * Install dependencies using npm.
 */
async function installDependencies() {
  try {
    console.log('Installing dependencies...');

    // Install in root
    console.log('Running npm install in project root...');
    execSync('npm install', {
      cwd: ROOT_DIR,
      stdio: 'inherit'
    });

    // Install in theme directory if it exists
    const themeDir = path.dirname(THEME_PACKAGE_JSON);
    try {
      await fs.access(themeDir);
      console.log(`Running npm install in ${themeDir}...`);
      execSync('npm install', {
        cwd: themeDir,
        stdio: 'inherit'
      });
    } catch (error) {
      console.log(`Theme directory not found at ${themeDir}, skipping npm install there`);
    }
  } catch (error) {
    console.error('Error installing dependencies:', error.message);
    throw error;
  }
}

// Run the main function
main().catch(error => {
  console.error('Fatal error:', error);
  process.exit(1);
});
