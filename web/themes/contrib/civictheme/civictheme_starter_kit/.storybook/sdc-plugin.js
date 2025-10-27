import fs from 'fs';
import path from 'path';
import { globSync } from 'glob';

/**
 * Scans story files and their dependencies to find and return a list of
 * component assets.
 *
 * This function recursively scans story files, their data files and Twig
 * templates to find all component dependencies. It looks for:
 * - Direct Twig imports in story files
 * - Nested data file imports
 * - Twig includes within templates
 *
 * For each found component, it checks for and collects paths to:
 * - CSS files
 * - JS files (excluding story files)
 *
 * @param {string} storiesPath - Path to the story file to scan
 * @param {string} componentDirectory - Root directory containing all components
 * @param {string[]} namespaces - Array of supported include namespaces
 * @returns {string[]} Array of paths to component assets
 */
function getDependencyImports(storiesPath, componentDirectory, namespaces = ['civictheme']) {
  const dir = path.dirname(storiesPath);
  const includeRegex = new RegExp(`include\\s+'(${namespaces.join('|')}):([^']+)'`, 'g');
  const twigFiles = new Set(); // Store unique twig files to scan
  const scannedFiles = new Set(); // Prevent infinite recursion

  // Scans a data file for twig imports and nested data imports
  const scanDataFile = (dataPath, currentDir) => {
    if (!fs.existsSync(dataPath)) return;

    const dataContent = fs.readFileSync(dataPath, 'utf8');

    // Add twig imports
    dataContent.matchAll(/import.*from\s+'([^']*\.twig)'/g).forEach((match) => twigFiles.add(path.resolve(currentDir, match[1])));

    // Scan nested data files
    dataContent.matchAll(/import.*from\s+'([^']*\.stories\.data)'/g).forEach((match) => {
      const nestedPath = path.resolve(currentDir, `${match[1]}.js`);
      scanDataFile(nestedPath, path.dirname(nestedPath));
    });
  };

  // Scans a twig file for includes and adds dependencies
  const scanTwigFile = (twigPath) => {
    if (scannedFiles.has(twigPath)) return;
    scannedFiles.add(twigPath);

    const twigContent = fs.readFileSync(twigPath, 'utf8');
    twigContent.matchAll(includeRegex).forEach((match) => {
      const componentName = match[2];
      const dependencyPath = globSync(`${componentDirectory}/**/${componentName}.twig`);
      if (dependencyPath.length > 0) {
        const dependencyTwigPath = dependencyPath.pop();
        twigFiles.add(dependencyTwigPath);
      }
    });
  };

  // Initial scan of stories file
  if (fs.existsSync(storiesPath)) {
    const storiesContent = fs.readFileSync(storiesPath, 'utf8');

    // Add direct twig imports
    storiesContent.matchAll(/import.*from\s+'([^']*\.twig)'/g).forEach((match) => twigFiles.add(path.resolve(dir, match[1])));

    // Scan data files
    storiesContent.matchAll(/import.*from\s+'([^']*\.stories\.data)'/g).forEach((match) => scanDataFile(path.resolve(dir, `${match[1]}.js`), dir));
  }

  // Process all found twig files
  twigFiles.forEach(scanTwigFile);

  // Generate imports for all found components
  const imports = [];
  scannedFiles.forEach((twigPath) => {
    const componentDir = path.dirname(twigPath);
    const componentName = path.basename(twigPath, '.twig');

    // Add CSS if exists
    const cssPath = path.join(componentDir, `${componentName}.css`);
    if (fs.existsSync(cssPath)) {
      const relativePath = path.relative(dir, cssPath);
      const importPath = relativePath.startsWith('.') ? relativePath : `./${relativePath}`;
      const suffix = componentName.endsWith('.stories') ? '?module' : '';
      imports.push(`${importPath}${suffix}`);
    }

    // Add JS if exists and not a stories file
    if (!componentName.endsWith('.stories')) {
      const jsPath = path.join(componentDir, `${componentName}.js`);
      if (fs.existsSync(jsPath)) {
        const relativePath = path.relative(dir, jsPath);
        imports.push(relativePath.startsWith('.') ? relativePath : `./${relativePath}`);
      }
    }
  });

  return [...new Set(imports)].sort();
}

export default (options = {}) => ({
  name: 'sdc-plugin',
  enforce: 'pre',
  transform: (code, id) => {
    const componentDir = path.resolve(__dirname, options.path || '../components');
    const isWithinComponentDir = id.indexOf(componentDir) >= 0;
    const namespaces = options.namespaces || ['civictheme'];

    if (isWithinComponentDir) {
      // For stories - resolve their dependencies.
      if (id.endsWith('.stories.js')) {
        const imports = getDependencyImports(id, componentDir, namespaces).map((i) => `import '${i}';`).join('\n');
        return {
          code: `${imports}\n${code}`,
          map: null,
        };
      }

      // For component js files - wrap in DOMContentLoaded event.
      if (id.endsWith('.js') && !id.endsWith('stories.data.js')) {
        return {
          code: `document.addEventListener('DOMContentLoaded', () => {\n${code}\n});`,
          map: null,
        };
      }
    }

    return null;
  },
});
