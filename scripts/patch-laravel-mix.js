#!/usr/bin/env node
// laravel-mix 6.0.37's BuildOutputPlugin.js imports `formatSize` from
// `webpack/lib/SizeFormatHelpers`. Newer webpack 5.x releases removed that
// internal module, so `mix`/`mix watch` crashes with:
//   Error: Cannot find module 'webpack/lib/SizeFormatHelpers'
// This patch replaces the require with an inline `formatSize` implementation
// (identical to webpack's original), removing the dependency on the internal
// module regardless of the installed webpack version.
const fs = require('fs');
const { execSync } = require('child_process');

const INLINE = `const formatSize = size => {
	if (typeof size !== "number" || Number.isNaN(size) === true) {
		return "unknown size";
	}
	if (size <= 0) {
		return "0 bytes";
	}
	const abbreviations = ["bytes", "KiB", "MiB", "GiB"];
	const index = Math.floor(Math.log(size) / Math.log(1024));
	return \`\${+(size / Math.pow(1024, index)).toPrecision(3)} \${abbreviations[index]}\`;
};`;

// Matches: let|const|var { formatSize } = require('webpack/lib/SizeFormatHelpers');
const requireRe = /(?:let|const|var)\s*\{\s*formatSize\s*\}\s*=\s*require\(\s*['"]webpack\/lib\/SizeFormatHelpers['"]\s*\)\s*;?/;

const output = execSync(
  'find node_modules -path "*/laravel-mix/src/webpackPlugins/BuildOutputPlugin.js"',
  { encoding: 'utf8' }
);

output.trim().split('\n').filter(Boolean).forEach(file => {
  try {
    let src = fs.readFileSync(file, 'utf8');
    if (src.includes('// patched-inline-formatSize')) {
      return; // already patched
    }
    if (requireRe.test(src)) {
      src = src.replace(requireRe, INLINE + '\n// patched-inline-formatSize');
      fs.writeFileSync(file, src);
      console.log('Patched:', file);
    }
  } catch (_) {}
});
