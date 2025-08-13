const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

// Define paths
const nvmrcPath = path.resolve('.nvmrc');
const targetPath = path.resolve('./themes/wds-bt/.nvmrc');
const tempScriptPath = path.resolve('temp_nvm_script.sh');

// Remove existing .nvmrc file if it exists.
try {
  const stats = fs.lstatSync(nvmrcPath);
  if (stats.isSymbolicLink() || stats.isFile()) {
    fs.unlinkSync(nvmrcPath);
  }
} catch (e) {
  // Ignore error if .nvmrc does not exist.
}

// Create a symlink to the .nvmrc file in the target directory.
fs.symlinkSync(targetPath, nvmrcPath, 'file');

// Function to create and execute a temporary shell script for nvm commands.
function execNvmCommand(command) {
  const nvmSource = process.platform === 'win32' ? 'nvm' : '. "$NVM_DIR/nvm.sh" &&';
  const scriptContent = `
    #!/bin/sh
    ${nvmSource} ${command}
  `;
  fs.writeFileSync(tempScriptPath, scriptContent);

  // Make the script executable.
  fs.chmodSync(tempScriptPath, '755');

  // Execute the script using proper escaping.
  execSync(`"${tempScriptPath}"`, { stdio: 'inherit', shell: true });

  // Remove the temporary script.
  fs.unlinkSync(tempScriptPath);
}

// Run nvm commands.
execNvmCommand('nvm install');
execNvmCommand('nvm use');
