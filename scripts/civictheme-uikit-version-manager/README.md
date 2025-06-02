## CivicTheme UI Kit Version Change Tool

This tool allows developers to easily switch between different versions of the CivicTheme UI Kit.

## Overview

CivicTheme UI Kit is located at:
- GitHub Repository: https://github.com/civictheme/uikit/
- NPM Package: https://www.npmjs.com/package/@civictheme/uikit

## Features

This tool enables switching the UI Kit version to:
- NPM released versions
- Main branch of the UI Kit repository
- Any development/feature branch of the UI Kit repository

## Usage

Run the tool from the project root with:

```
npm run uikit-change
```

## How It Works

The tool will ask you what version of CivicTheme UI Kit you want to install:

1. **Release** - Install from NPM
   - `@civictheme/sdc` will be the installed package
   - You'll be asked to specify a version (or use 'latest')
   - The tool will update package.json files to use this release
   - It will then run npm install to implement the changes

2. **Dev** - Install from GitHub branch
   - `@civictheme/uikit` will be the source for the installation
   - You'll be presented with all available branches from the CivicTheme repository
   - After selecting a branch, the tool will update package.json files
   - Select the latest commit in the repository
   - It will then run npm install to implement the changes


